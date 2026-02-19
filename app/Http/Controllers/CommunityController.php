<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function create()
    {
        return view('community.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'address' => 'required',
            'barangay' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'privacy' => 'required'
        ]);

        // Save to database here later

        return back()->with('success', 'Registration submitted successfully!');
    }
}

