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
            $user = Auth::user();

            // Check if user is archived or inactive
            if ($user->archived_at !== null || !$user->is_active) {
                Auth::logout();
                return back()->with('error', 'This user account is inactive or archived. Contact administrator.');
            }

            $request->session()->regenerate();

            // Role-based redirect
            if ($user->role === 'IT') {
                return redirect()->route('it.dashboard');
            }

            if ($user->role === 'CESO') {
                return redirect()->route('ceso.dashboard');
            }

            if ($user->role === 'Faculty') {
                return redirect('/'); // Temporary: redirect to home
            }

            if ($user->role === 'Community') {
                return redirect()->route('community.dashboard');
            }

            return redirect('/');
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
