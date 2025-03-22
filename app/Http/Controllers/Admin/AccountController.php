<?php

namespace App\Http\Controllers\Admin;

use PDO;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        
        $search = request()->query('search', '');
        $cashFilter = intval(request()->query('cash')) === 1;
        $currentPage = request()->query('page', 1);
        
        // Start building the base query with consistent eager loading
        $query = Account::with('account_detail')->withCount('no_of_characters');
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->whereAny(['account'], 'LIKE', "%{$search}%");
            $currentPage = 1; // Reset to first page when searching
        }
        
        // Apply cash filter if requested
        if ($cashFilter) {
            $query->where('cash', '>', 0);
            $currentPage = 1; 
        }
        
        return inertia('Admin/Users', [
            'users' => $query->paginate(10),
            'currentPage' => $currentPage,
            'search' => $search,
            'cashFilter' => $cashFilter,
        ]);
    }

    public function show(string $account)
    {
        $account = Account::with(['account_detail', 'purchases'])->find($account);

        return inertia('Admin/UserProfile', [
            'user' => $account,
        ]);
    }

    public function modifyPoints(Request $request, string $account)
    {
        $request->validate([
            'cash' => 'required|numeric|max:9999',
            'votepoint' => 'required|numeric|max:9999',
        ]);

        return DB::transaction(function () use ($account, $request) {
            $account = Account::find($account, ['account', 'cash', 'votepoint']);
            $account->votepoint = $request->votepoint;
            $account->save();

            $username = $account->account;
            $cash = $request->cash;
            $db = DB::connection('ACCOUNT_DBF');
            $stmt = $db->getPdo()->prepare('EXEC dbo.usp_UpdateCashInfo ?, ?');
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $cash);
            $stmt->execute();
            return response()->json(['message' => 'Points updated successfully']);
        });

    }

    public function updateAccountCredentials(Request $request, string $userAccount)
    {
        $request->validate([
            'username' => 'required|unique:ACCOUNT_DBF.ACCOUNT_TBL,account,'.$userAccount.',account',
            'password' => 'nullable',
        ]);

        return DB::transaction(function () use ($request, $userAccount) {
            $password = md5(config('app.game_hash') . $request->password);

            $db = DB::connection('ACCOUNT_DBF');
            $stmt = $db->getPdo()->prepare('EXEC dbo.usp_og_ChangePassWord ?, ?');
            $stmt->bindParam(1, $userAccount);
            $stmt->bindParam(2, $password);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_NUM);
            if ($result[0] == 1) {
                return response()->json([
                    'message' => 'Account updated successfully',
                    'new_username' => $request->username,
                ]);
            }
        });
    }


    public function banAccount(Request $request, string $account)
    {
        $request->validate([
            'date' => 'required|date|date_format:Ymd',
        ], [
            'date.date_format' => 'The date format must be Year, Month, Day (Ymd)',
        ]);

        $account = AccountDetail::find($account);
        $account->BlockTime = $request->date;
        $account->save();

        return response()->json([
            'message' => 'Account banned successfully',
        ]);
    }

    public function characters(string $account)
    {
        $account = Account::with('characters')->find($account);
        return inertia('Admin/UserCharacters', [
            'account' => $account,
        ]);
    }
}
