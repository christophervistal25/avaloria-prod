<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\DonateGCash;
use App\Models\DonatePaypal;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    public function index(Request $request)
    {
        $accounts = AccountDetail::with('account_information:account,cash,votepoint')
                                ->where('email', $request->user()->email)->get(['account', 'email']);
        $donates = DonatePaypal::where('is_active', true)->get();
        $gcashDonates = DonateGCash::where('is_active', true)->get();

        return inertia('Donates', [
            'isLoggedin' => $request->user() ? true : false,
            'csrf_token' => csrf_token(),
            'donates' => $donates,
            'paypalDonates' => $donates,
            'gcashDonates' => $gcashDonates,
            'accounts' => $accounts,
        ]);
    }
}
