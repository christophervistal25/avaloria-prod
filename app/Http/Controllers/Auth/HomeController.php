<?php

namespace App\Http\Controllers\Auth;

use PDO;
use App\Models\User;
use App\Models\VoteLog;
use App\Models\Character;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use App\Service\RedeemCodeService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if(!request()->user()) {
            return redirect()->to('/');
        }
        
        $accountPurchases = User::with([
            'gCashDonates' => function ($query) {
                $query->where('status', 'success')->orWhere('status', 'on_process')->orWhere('status', 'cancelled');
            },
            'gCashDonates.donateGCash',
            'paypalDonates' => function ($query) {
                $query->where('status', 'completed');
            },
            'paypalDonates.donatePaypal'
        ])->find(auth()->user()->id);

        $userTotalDonateInGcash = 0;
        $userTotalDonateInPaypal = 0;


        $accountPurchases->gCashDonates->filter(function($donate) {
            return $donate->status !== 'cancelled';
        })->each(function ($donate) use(&$userTotalDonateInGcash) {
            $userTotalDonateInGcash += $donate->donateGCash->amount;
        });

        $accountPurchases->paypalDonates->each(function ($donate) use(&$userTotalDonateInPaypal) {
            $userTotalDonateInPaypal += $donate->donatePaypal->amount;
        });

        $accounts = AccountDetail::with(['account_information', 'account_information.characters'])->where('email', auth()->user()->email)->orderBy('regdate', 'DESC')->get();
        $accountNames = AccountDetail::where('email', auth()->user()->email)?->get()?->pluck('account')->toArray();
        $characters = Character::whereIn('account', $accountNames)->get(['m_idPlayer', 'm_szName']);

        return inertia('User/Dashboard', [
            'characters' => $characters,
            'gCashPurchases' => $accountPurchases->gCashDonates,
            'paypalPurchases' => $accountPurchases->paypalDonates,
            'userTotalDonateInGcash' => $userTotalDonateInGcash,
            'userTotalDonateInPaypal' => $userTotalDonateInPaypal,
            'accounts' => $accounts,
            'voteLogs' => VoteLog::where('account', auth()->user()->account?->account)
                    ->latest('voted_at')
                    ->get()
        ]);
    }

    public function createInGameAccount(Request $request)
    {
            $request->merge([
                'email' => auth()->user()->email,
            ]);
            $request->validate([
                'username' => ['required', 'string', 'max:32', 'unique:ACCOUNT_DBF.dbo.ACCOUNT_TBL,account'],
                'password' => ['required', 'string', 'min:8', 'same:confirmed_password'],
                'email' => ['required', 'string', 'email', 'max:100', 'exists:WEBSITE_DBF_CONNECTION.dbo.users,email'],
            ]);
    
            $password = md5(config('app.game_hash') . $request->password);
            $email = auth()->user()->email;
            $account = $request->username;
            $cash = 0;
    
            $db = DB::connection('ACCOUNT_DBF');
            $stmt = $db->getPdo()->prepare('EXEC dbo.usp_CreateNewAccount ?, ?, ?, ?');
            $stmt->bindParam(1, $account);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $cash);
            $stmt->bindParam(4, $email);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if ($result[0] == 1) {
                return response()->json([
                    'message' => 'Account created successfully!',
                ], 201);
            }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'different:current_password'],
            'confirm_password' => ['required', 'string', 'same:new_password'],
        ]);

        $user = auth()->user();
        $currentPassword = $request->current_password;
        $newPassword = $request->new_password;

        if (!password_verify($currentPassword, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully!',
        ], 200);
    }

    public function redeemCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'selectedAccount' => 'required|string'
        ]);
        $redeemService = new RedeemCodeService();

        $accountDetail = AccountDetail::where('email', auth()->user()->email)->first();

        $result = $redeemService->redeemCode(
            $request->code,
            $request->selectedAccount,
            $accountDetail->account
        );

        if ($result['success']) {
            return response()->json([
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                $result,
            ], 422);
        }
    }
} 
