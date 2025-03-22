<?php

namespace App\Http\Controllers\Auth;

use PDO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class LoginController extends Controller
{
    use AuthorizesRequests;
    
    public function username()
    {
        return 'username';
    }
    
    public function submitLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->username)->first(['username']);

        $username = $user->username;
        $password = $request->password;

        if (Auth::attempt([
            'username' => $username,
            'password' => $password,
        ])) {
            return response()->json(['message' => 'Success!'], 200);
        }
         else {
            return response()->json(['message' => 'Check your Username or Password'], 422);
        }
    }
}
