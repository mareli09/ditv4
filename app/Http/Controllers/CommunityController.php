<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CommunityController extends Controller
{
    public function create()
    {
        return view('community.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string',
            'address' => 'required|string',
            'barangay' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'joined' => 'nullable|string',
            'privacy' => 'required|accepted'
        ]);

        // Create user account for community member
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('default123'), // Default password, can change after login
            'role' => 'Community',
            'is_active' => 1
        ]);

        // Store community profile data
        $user->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'barangay' => $validated['barangay'],
            'phone' => $validated['phone'],
            'previously_joined' => $validated['joined'] === 'yes' ? 1 : 0
        ]);

        return back()->with('success', 'Registration successful! You can now login with your email and password "default123". Please change your password after login.');
    }
}

