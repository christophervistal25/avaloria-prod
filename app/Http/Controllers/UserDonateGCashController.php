<?php

namespace App\Http\Controllers;

use App\Models\DonateGCash;
use Illuminate\Http\Request;
use App\Models\UserGCashDonate;

class UserDonateGCashController extends Controller
{
    public function VerifyRequest(Request $request)
    {
        $transactionId = 'GCASH_' . time() . rand(1000, 9999);
        $temporaryReferenceNumber = '*_' . time();
        $transaction = UserGCashDonate::create([
            'user_id' => $request->user()->id,
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'donate_g_cash_id' => $request->donate,
            'reference_number' => $temporaryReferenceNumber,
            'account' => $request->idx,
        ]);

        return redirect()->route('gcash-checkout', $transaction->id);
    }

    public function GcashCheckOut(string $id)
    {
        $transaction = UserGCashDonate::where('status', 'pending')->findOrFail($id);
        $gCashRecord = DonateGCash::findOrFail($transaction->donate_g_cash_id);
        return inertia('Auth/GcashDonateCheckout', [
            'transaction' => $transaction,
            'gCashRecord' => $gCashRecord,
        ]);
    }

    public function GCashCheckoutVerify(Request $request)
    {
        $request->validate([
            'transaction_id' => ['required', 'exists:user_g_cash_donates,transaction_id'],
            'reference_number' => ['required', 'unique:user_g_cash_donates,reference_number'],
        ], [
            'transaction_id.exists' => 'Invalid transaction id',
            'reference_number.unique' => 'Reference number already used',
            'reference_number.required' => 'Please provide reference number from your GCash transaction',
        ]);


        UserGCashDonate::where('status', 'pending')->where('transaction_id', $request->transaction_id)->update([
            'status' => 'on_process',
            'reference_number' => $request->reference_number,
        ]);

        return response()->json(['message' => 'Transaction verified successfully']);
    }
}
