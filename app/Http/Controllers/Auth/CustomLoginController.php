<?php

// app/Http/Controllers/Auth/CustomLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.custom-login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/src');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}
