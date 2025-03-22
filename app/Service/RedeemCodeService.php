<?php

namespace App\Service;

use App\Models\RedeemCode;
use Illuminate\Http\Request;
use App\Models\RedeemHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RedeemCodeService
{
    /**
     * Redeem a code for the specified character
     *
     * @param string $code The redemption code
     * @param string $characterId The character ID receiving the item
     * @param string $accountName The account username
     * @return array Result with status and message
     */
    public function redeemCode($code, $characterId, $accountName)
    {
        $codeDetails = $this->validateCode($code);
        if (!$codeDetails['valid']) {
            return $codeDetails;
        }

        try {
            DB::transaction(function () use ($code, $characterId, $accountName) {
                // Update code status to closed and add redeemer info
                $code = RedeemCode::where('code', $code)
                    ->with('details')
                    ->first();

                    foreach($code->details as $codeDetails) {
                        DB::connection('CHARACTER_DBF_CONNECTION')
                        ->table('ITEM_SEND_TBL')
                        ->insert([
                            'm_idPlayer' => $characterId,
                            'serverindex' => '01',
                            'Item_Name' => $codeDetails->item_code,
                            'Item_count' => $codeDetails->item_quantity,
                            'm_nAbilityOption' => 0,
                            'm_bItemResist' => 0,
                            'm_nResistAbilityOption' => 0,
                            'idSender' => '0000000',
                            'nPiercedSize' => 0,
                        ]);
                    }

                    $code->status = 'claimed';
                    $code->save();

                    RedeemHistory::create([
                        'code' => $code->code,
                        'account_name' => $accountName,
                        'character_id' => $characterId,
                    ]);
            });

            return [
                'success' => true,
                'message' => 'Code redeemed successfully! Your item has been sent to your character.',
            ];
        } catch (QueryException $e) {
            return [
                'success' => false,
                'message' => 'Error processing redemption. Please try again later.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate if a code exists and is available
     *
     * @param string $code The redemption code to validate
     * @return array Validation result with details
     */
    private function validateCode($code)
    {
        $codeRecord = RedeemCode::with('details')->where('code', $code)->first();

        if (!$codeRecord) {
            return [
                'valid' => false,
                'success' => false,
                'message' => 'Invalid redemption code.'
            ];
        }

        if ($codeRecord->status !== 'active') {
            return [
                'valid' => false,
                'success' => false,
                'message' => 'This code has already been redeemed or is no longer valid.'
            ];
        }

        // Check if code has expired
        if ($codeRecord->expires_at && now()->greaterThan($codeRecord->expires_at)) {
            return [
                'valid' => false,
                'success' => false,
                'message' => 'This redemption code has expired.'
            ];
        }

        return [
            'valid' => true,
        ];
    }

    /**
     * Get all available characters for an account
     * 
     * @param string $accountName The account username
     * @return array List of characters
     */
    public function getAccountCharacters($accountName)
    {
        return DB::connection('CHARACTER_DBF')
            ->table('CHARACTER_TBL')
            ->select('m_idPlayer', 'm_szName')
            ->where('account', $accountName)
            ->where('isblock', 'F')
            ->where('m_chDeleteFlag', 'F')
            ->get()
            ->toArray();
    }
}