<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->user_type === 'Admin') {
            return redirect()->route('welcome');
        }

        return view('users.admin.admin_login');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->user_type === 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return redirect()->back()->with('error', 'Access denied. Not an admin.');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }

}
