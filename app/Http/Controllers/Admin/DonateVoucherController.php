<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameMail;
use App\Models\GuildBank;
use Illuminate\Http\Request;
use App\Models\CharacterBank;
use App\Models\CharacterChest;
use App\Models\CharacterInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\RefreshDonateVouchersCache;
use App\Service\FlyffInventoryParserService;

class DonateVoucherController extends Controller
{
    private array $voucherIds = ['7652', '7653', '7654', '7655'];
    private readonly FlyffInventoryParserService $inventoryParser;
    private const CACHE_TTL = 3600; // 1 hour cache

    public function __construct(FlyffInventoryParserService $inventoryParser)
    {
        $this->inventoryParser = $inventoryParser;
    }

    public function index(Request $request)
    {
        $cacheKey = 'donate_vouchers_summary';
        $forceRefresh = $request->query('refresh', false);
        $isProcessing = Cache::has($cacheKey . '_processing');
        
        // If force refresh is requested and no refresh is already in progress
        if ($forceRefresh && !$isProcessing) {
            // Clear existing cache to force regeneration
            Cache::forget($cacheKey);
            
            // Dispatch job to refresh the cache in the background
            dispatch(new RefreshDonateVouchersCache());
            
            return response()->json([
                'status' => 'refreshing',
                'message' => 'Refresh initiated. Please check back in a few minutes.',
                'last_cached_at' => Cache::get($cacheKey . '_timestamp'),
            ]);
        }
        
        // If refresh is already in progress
        if ($isProcessing) {
            return response()->json([
                'status' => 'processing',
                'message' => 'Data refresh is currently in progress.',
                'last_cached_at' => Cache::get($cacheKey . '_timestamp'),
            ]);
        }
        
        // If cached data exists, return it
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            return response()->json(array_merge($cachedData, [
                'status' => 'cached',
                'cache_info' => [
                    'cached_at' => Cache::get($cacheKey . '_timestamp'),
                    'cache_age' => now()->diffInMinutes(Cache::get($cacheKey . '_timestamp')) . ' minutes',
                    'can_refresh' => true
                ]
            ]));
        }
        
        // If we get here, no cache exists and no refresh is in progress
        // Process data and cache the results immediately
        Cache::put($cacheKey . '_processing', true, 1800);
        
        try {
            $result = $this->processVouchers();
            $now = now();
            
            // Add metadata
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
            
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            Cache::put($cacheKey . '_timestamp', $now, self::CACHE_TTL);
            
            $result['status'] = 'fresh';
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("Error processing vouchers: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing vouchers.'
            ], 500);
        } finally {
            Cache::forget($cacheKey . '_processing');
        }
    }
    
    /**
     * Process all vouchers with optimizations for scale
     */
    private function processVouchers(): array
    {
        return [
            'inventories_vouchers' => $this->getOptimizedVouchersFromSource(
                CharacterInventory::class, 
                'm_idPlayer', 
                'm_Inventory'
            ),
            'guild_inventories_vouchers' => $this->getOptimizedVouchersFromSource(
                GuildBank::class, 
                'm_idGuild', 
                'm_GuildBank'
            ),
            'bank_vouchers' => $this->getOptimizedVouchersFromSource(
                CharacterBank::class, 
                'm_idPlayer', 
                'm_Bank'
            ),
            'character_chest_vouchers' => $this->getOptimizedVouchersFromSource(
                CharacterChest::class, 
                'm_idPlayer', 
                'm_Chest'
            ),
            'voucher_mails' => $this->getVouchersFromMail(),
        ];
    }
    
    /**
     * Get vouchers with pre-filtering at the database level where possible
     */
    private function getOptimizedVouchersFromSource(string $modelClass, string $idColumn, string $inventoryColumn): array
    {
        $results = [];
        $idKey = str_contains($idColumn, 'Guild') ? 'guild_id' : 'character_id';
        $voucherPatterns = $this->generateVoucherSearchPatterns();
        
        try {
            // Pre-filter at the database level to reduce the number of rows we process
            $query = $modelClass::select([$idColumn, $inventoryColumn]);
            
            // Apply LIKE filters to catch inventories containing our voucher IDs
            foreach ($voucherPatterns as $pattern) {
                $query->orWhere($inventoryColumn, 'LIKE', $pattern);
            }
            
            // Process matching records in manageable chunks
            $query->chunk(50, function($items) use (&$results, $idColumn, $inventoryColumn, $idKey) {
                foreach ($items as $item) {
                    // Parse only the pre-filtered inventories
                    $inventory = $this->inventoryParser->parse($item->$inventoryColumn);
                    $foundVouchers = $this->inventoryParser->findItemsByIds($inventory, $this->voucherIds);
                    
                    foreach ($foundVouchers as $voucher) {
                        $results[] = [
                            $idKey => $item->$idColumn,
                            'item_id' => $voucher['item_id'] ?? null,
                            'quantity' => $voucher['quantity'] ?? null,
                            'serial_number' => $voucher['serial_number'] ?? null,
                        ];
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error("Error processing vouchers from {$modelClass}: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Generate SQL LIKE patterns to pre-filter inventory strings containing voucher IDs
     */
    private function generateVoucherSearchPatterns(): array
    {
        $patterns = [];
        foreach ($this->voucherIds as $id) {
            // This pattern looks for the item ID in the second position of the inventory item format
            // The comma ensures we match the exact item ID rather than substring matches
            $patterns[] = "%,{$id},%";
        }
        return $patterns;
    }
    
    /**
     * Get vouchers from mail system with database-level filtering
     */
    private function getVouchersFromMail(): array
    {
        try {
            // Directly query and return without loading into memory
            return GameMail::where('ItemFlag', 0)
                ->whereIn('dwItemId', $this->voucherIds)
                ->select([
                    'idReceiver as character_id', 
                    'dwItemId as item_id', 
                    'nItemNum as quantity', 
                    'dwSerialNumber as serial_number'
                ])
                ->limit(10000) // Safety limit
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error("Error processing vouchers from mail: " . $e->getMessage());
            return [];
        }
    }
}