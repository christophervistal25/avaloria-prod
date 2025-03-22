<?php

namespace App\Http\Controllers\Auth;

use PDO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        session()->put('page_title', 'Create an Account');
        session()->put('page_description', 'Create an Account');
        return inertia('SignUp');
    }

    public function submitRegisterForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'username' => (string) str()->uuid(),
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        auth()->login($user);

        return response()->json(['message' => 'Account created successfully.']);
    }
}
