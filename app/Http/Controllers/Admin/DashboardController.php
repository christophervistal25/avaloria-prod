<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Models\Character;
use Illuminate\Http\Request;
use App\Models\UserGCashDonate;
use Illuminate\Validation\Rule;
use App\Models\UserPaypalDonate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $gcashDonates = UserGCashDonate::with(['user', 'user.account', 'donateGCash', 'account_information'])
                                ->when(!is_null(request('status')), function ($query) {
                                    if(request('status') == 'all') {
                                        return $query;
                                    }
                                    return $query->where('status', request('status'));
                                })
                                ->when(!is_null(request('search')), function ($query) {
                                    $query->where('reference_number', 'like', '%' . request('search') . '%');
                                })
                                ->where('status', '!=', 'cancelled')
                                ->orderBy('created_at', 'DESC')
                                ->paginate(8, ['*'], 'gcash_page')
                                ->withQueryString();


        $paypalDonates = UserPaypalDonate::with(['user', 'user.account', 'donatePaypal', 'account_information'])
                                ->when(!is_null(request('paypal_search')), function ($query) {
                                    $query->where('reference_number', 'like', '%' . request('paypal_search') . '%');
                                })
                                ->orderBy('created_at', 'DESC')
                                ->paginate(8, ['*'], 'paypal_page')
                                ->withQueryString();
    
        $totalDonateInGCash = UserGCashDonate::with('donateGCash')->where('status', 'success')->get()->sum(fn($data) => $data->donateGCash->amount ?? 0);
        $totalDonateInPaypal = UserPaypalDonate::with('donatePaypal')->where('status', 'success')->get()->sum(fn($data) => $data->donatePaypal->amount ?? 0);
        $totalNoOfGCashDonates = UserGCashDonate::where('status', 'success')->count();
        $totalNoOfPaypalDonates = UserPaypalDonate::where('status', 'success')->count();
        $totalNoOfAccounts = Account::count();
        $totalNoOfCharacters = Character::count();


        return inertia('Admin/Dashboard', [
            'gcashDonates' => $gcashDonates,
            'paypalDonates' => $paypalDonates,
            'paypalSearch' => request('search_paypal', ''),
            'gcashStatus' => request('status', 'all'),
            'gcashSearch' => request('search', ''),
            'totalDonateInGCash' => number_format($totalDonateInGCash, 2, ".", ","),
            'totalDonateInPaypal' => number_format($totalDonateInPaypal, 2, ".", ","),
            'totalNoOfGCashDonates' => $totalNoOfGCashDonates,
            'totalNoOfPaypalDonates' => $totalNoOfPaypalDonates,
            'totalNoOfAccounts' => $totalNoOfAccounts,
            'totalNoOfCharacters' => $totalNoOfCharacters,
        ]);
    }

    public function sendCashWithGCash(Request $request)
    {
        $request->validate([
            'idx' => ['required', Rule::exists('user_g_cash_donates', 'id')->where('status', 'on_process')],
        ], [
            'idx.exists' => 'Please select a on process transaction.',
        ]);

        return DB::transaction(function () use ($request) {
            $idx = $request->idx;

            $userGCashDonate = UserGCashDonate::with(['user', 'user.account', 'donateGCash', 'account_information'])->find($idx);
            $account = $userGCashDonate->account_information;

            $amount = $userGCashDonate->donateGCash->equivalent_donate_points;
            $currentCash = $account->cash;
            $userAccount = $account->account;
            $userModifiedCash = intval($currentCash) + intval($amount);

            $db = DB::connection('ACCOUNT_DBF');
            $stmt = $db->getPdo()->prepare('EXEC dbo.usp_UpdateCashInfo ?, ?');
            $stmt->bindParam(1, $userAccount);
            $stmt->bindParam(2, $userModifiedCash);
            $stmt->execute();

            $userGCashDonate->status = 'success';
            $userGCashDonate->save();

            return response()->json(['message' => 'Transaction success.']);
        });
    }

    public function deleteCashWithGCash(Request $request)
    {
        $request->validate([
            'idx' => ['required', Rule::exists('user_g_cash_donates', 'id')->where(function($query) {
                $query->where('status', 'on_process')->orWhere('status', 'pending');
            })],
        ], [
            'idx.exists' => 'Please select a on process transaction.',
        ]);

        return DB::transaction(function () use ($request) {
            $idx = $request->idx;

            $userGCashDonate = UserGCashDonate::with(['user', 'user.account', 'donateGCash', 'account_information'])
                                            ->where('status', 'on_process')
                                            ->orWhere('status', 'pending')
                                            ->find($idx);

            $userGCashDonate->status = 'cancelled';
            $userGCashDonate->save();

            return response()->json(['message' => 'Transaction cancelled']);
        });
    }
}
