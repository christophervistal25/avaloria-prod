<?php

namespace App\Jobs;

use App\Models\GameMail;
use App\Models\GuildBank;
use App\Models\CharacterBank;
use App\Models\CharacterChest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\CharacterInventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Service\FlyffInventoryParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RefreshDonateVouchersCache implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1800; // 30 minutes

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return 'refresh_donate_vouchers_cache';
    }

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // No constructor parameters needed
    }

    /**
     * Execute the job.
     */
    public function handle(FlyffInventoryParserService $inventoryParser)
    {
        $cacheKey = 'donate_vouchers_summary';
        $voucherIds = ['7652', '7653', '7654', '7655'];

        try {
            // Set a processing flag to indicate work is happening
            Cache::put($cacheKey . '_processing', true, 1800);
            
            Log::info('RefreshDonateVouchersCache: Starting voucher data collection');
            
            $result = [
                'inventories_vouchers' => $this->getOptimizedVouchersFromSource(
                    CharacterInventory::class, 
                    'm_idPlayer', 
                    'm_Inventory',
                    $voucherIds,
                    $inventoryParser
                ),
                'guild_inventories_vouchers' => $this->getOptimizedVouchersFromSource(
                    GuildBank::class, 
                    'm_idGuild', 
                    'm_GuildBank',
                    $voucherIds,
                    $inventoryParser
                ),
                'bank_vouchers' => $this->getOptimizedVouchersFromSource(
                    CharacterBank::class, 
                    'm_idPlayer', 
                    'm_Bank',
                    $voucherIds,
                    $inventoryParser
                ),
                'character_chest_vouchers' => $this->getOptimizedVouchersFromSource(
                    CharacterChest::class, 
                    'm_idPlayer', 
                    'm_Chest',
                    $voucherIds,
                    $inventoryParser
                ),
                'voucher_mails' => $this->getVouchersFromMail($voucherIds),
            ];
            
            // Add metadata about the cache
            $now = now();
            $result['metadata'] = [
                'cached_at' => $now->toIso8601String(),
                'expires_at' => $now->addHours(1)->toIso8601String(),
                'total_vouchers_found' => 
                    count($result['inventories_vouchers']) +
                    count($result['guild_inventories_vouchers']) +
                    count($result['bank_vouchers']) +
                    count($result['character_chest_vouchers']) +
                    count($result['voucher_mails'])
            ];
            
            // Store the results in the cache
            Cache::put($cacheKey, $result, 3600); // 1 hour TTL
            Cache::put($cacheKey . '_timestamp', $now, 3600);
            Cache::forget($cacheKey . '_processing');
            
            Log::info('RefreshDonateVouchersCache: Successfully cached voucher data', [
                'total_vouchers' => $result['metadata']['total_vouchers_found']
            ]);
            
        } catch (\Exception $e) {
            Log::error('RefreshDonateVouchersCache: Failed to refresh cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Remove the processing flag even if there was an error
            Cache::forget($cacheKey . '_processing');
            
            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }

    /**
     * Get vouchers with pre-filtering at the database level where possible
     */
    private function getOptimizedVouchersFromSource(
        string $modelClass, 
        string $idColumn, 
        string $inventoryColumn,
        array $voucherIds,
        FlyffInventoryParserService $inventoryParser
    ): array {
        $results = [];
        $idKey = str_contains($idColumn, 'Guild') ? 'guild_id' : 'character_id';
        $voucherPatterns = $this->generateVoucherSearchPatterns($voucherIds);
        
        try {
            // Pre-filter at the database level to reduce the number of rows we process
            $query = $modelClass::select([$idColumn, $inventoryColumn]);
            
            // Apply LIKE filters to catch inventories containing our voucher IDs
            $query->where(function($q) use ($voucherPatterns, $inventoryColumn) {
                foreach ($voucherPatterns as $index => $pattern) {
                    if ($index === 0) {
                        $q->where($inventoryColumn, 'LIKE', $pattern);
                    } else {
                        $q->orWhere($inventoryColumn, 'LIKE', $pattern);
                    }
                }
            });
            
            // Process matching records in manageable chunks with progress logging
            $count = 0;
            $query->chunk(50, function($items) use (&$results, $idColumn, $inventoryColumn, $idKey, $voucherIds, $inventoryParser, &$count) {
                foreach ($items as $item) {
                    // Parse only the pre-filtered inventories
                    $inventory = $inventoryParser->parse($item->$inventoryColumn);
                    $foundVouchers = $inventoryParser->findItemsByIds($inventory, $voucherIds);
                    
                    foreach ($foundVouchers as $voucher) {
                        $results[] = [
                            $idKey => $item->$idColumn,
                            'item_id' => $voucher['item_id'] ?? null,
                            'quantity' => $voucher['quantity'] ?? null,
                            'serial_number' => $voucher['serial_number'] ?? null,
                        ];
                    }
                }
                
                $count += count($items);
                if ($count % 500 === 0) {
                    Log::info("RefreshDonateVouchersCache: Processed {$count} items from {$modelClass}");
                }
            });
            
            Log::info("RefreshDonateVouchersCache: Completed processing {$count} items from {$modelClass}, found " . count($results) . " vouchers");
            
        } catch (\Exception $e) {
            Log::error("RefreshDonateVouchersCache: Error processing vouchers from {$modelClass}: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Generate SQL LIKE patterns to pre-filter inventory strings containing voucher IDs
     */
    private function generateVoucherSearchPatterns(array $voucherIds): array
    {
        $patterns = [];
        foreach ($voucherIds as $id) {
            // This pattern looks for the item ID in the second position of the inventory item format
            // The comma ensures we match the exact item ID rather than substring matches
            $patterns[] = "%,{$id},%";
        }
        return $patterns;
    }
    
    /**
     * Get vouchers from mail system with database-level filtering
     */
    private function getVouchersFromMail(array $voucherIds): array
    {
        try {
            Log::info('RefreshDonateVouchersCache: Starting mail vouchers query');
            
            // Directly query and return without loading into memory
            $results = GameMail::where('ItemFlag', 0)
                ->whereIn('dwItemId', $voucherIds)
                ->select([
                    'idReceiver as character_id', 
                    'dwItemId as item_id', 
                    'nItemNum as quantity', 
                    'dwSerialNumber as serial_number'
                ])
                ->limit(10000) // Safety limit
                ->get()
                ->toArray();
                
            Log::info('RefreshDonateVouchersCache: Found ' . count($results) . ' vouchers in mail');
            return $results;
            
        } catch (\Exception $e) {
            Log::error("RefreshDonateVouchersCache: Error processing vouchers from mail: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::critical('RefreshDonateVouchersCache job failed', [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
        
        // Clear the processing flag so the job can be tried again
        Cache::forget('donate_vouchers_summary_processing');
    }
}