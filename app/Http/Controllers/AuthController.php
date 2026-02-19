<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {

            $request->session()->regenerate();

            // Role-based redirect
            $user = Auth::user();

            if ($user->role === 'IT') {
                return redirect()->route('it.dashboard');
            }

            if ($user->role === 'CESO') {
                return redirect('/'); // Temporary: redirect to home
            }

            if ($user->role === 'Faculty') {
                return redirect('/'); // Temporary: redirect to home
            }

            return redirect('/'); // Temporary: redirect to home
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
