<?php

namespace App\Http\Controllers\Auth;

use PDO;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $account = auth()->user();
        $account = Account::with('account_detail:account,email')->find($account->username, ['account']);

        return inertia('Auth/ShowProfile', [
            'info' => $account,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'password' => 'nullable|min:2|confirmed',
        ]);

        $userAccount = auth()->user()->username;

        if ($request->password) {
            $this->validate($request, [
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
                    User::where('username', $userAccount)->update([
                        'password' => bcrypt($request->password),
                    ]);

                    return response()->json([
                        'message' => 'Updated successfully',
                    ]);
                }
            });
        }

        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }
}
