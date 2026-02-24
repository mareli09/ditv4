<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the profile page for community users
     */
    public function communityShow()
    {
        abort_if(auth()->user()->role !== 'Community', 403);
        
        return view('community.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the profile page for IT users
     */
    public function itShow()
    {
        abort_if(auth()->user()->role !== 'IT', 403);
        
        return view('it.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the profile page for CESO users
     */
    public function cesoShow()
    {
        abort_if(auth()->user()->role !== 'CESO', 403);
        
        return view('ceso.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the profile page for Student users
     */
    public function studentShow()
    {
        abort_if(auth()->user()->role !== 'Student', 403);
        
        return view('student.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the profile page for Faculty users
     */
    public function facultyShow()
    {
        abort_if(auth()->user()->role !== 'Faculty', 403);
        
        return view('faculty.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the profile page for Staff users
     */
    public function staffShow()
    {
        abort_if(auth()->user()->role !== 'Staff', 403);
        
        return view('staff.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
