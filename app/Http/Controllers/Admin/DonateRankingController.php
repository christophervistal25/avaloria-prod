<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonateRankingController extends Controller
{
    public function index()
    {
        return inertia('Admin/DonateRanking');
    }

    public function fetchDonationRanking(Request $request)
    {

        $rankings = User::with([
            'gCashDonates' => function ($query) {
                $query->where('status', 'success')->orWhere('status', 'on_process')->select('id', 'donate_g_cash_id', 'transaction_id', 'reference_number', 'user_id', 'status', 'created_at');
            },
            'gCashDonates.donateGCash',
            'paypalDonates' => function ($query) {
                $query->where('status', 'completed')->select('id', 'donate_paypal_id', 'user_id', 'status', 'created_at');
            },
            'paypalDonates.donatePaypal',
            'account_details:account,email',
            'account_details.account_information:account,cash',
        ])->get();

        $rankings = $rankings->map(function ($user){
            $totalDonation = 0;

            // Include GCash donations if type is 'both' or 'gcash'
                $user->gCashDonates->filter(function ($donate) {
                    return $donate->status !== 'cancelled';
                })->each(function ($donate) use (&$totalDonation) {
                    $totalDonation += $donate->donateGCash->amount;
                });

                $user->paypalDonates->each(function ($donate) use (&$totalDonation) {
                    $totalDonation += $donate->donatePaypal->amount;
                });

            
            return [
                'user' => $user,
                'totalDonation' => $totalDonation,
            ];
        });

        $totalDonatePointsInAllAccounts = Account::sum('cash');

        $rankings = $rankings->sortByDesc('totalDonation')->values();

        return response()->json([
            'totalDonatePoints' => $totalDonatePointsInAllAccounts,
            'rankings' => $rankings,
        ]);
    }
}
