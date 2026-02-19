<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use App\Models\User;

class ITDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        return view('it.dashboard', [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('is_active', 1)->count(),
            'inactiveUsers' => User::where('is_active', 0)->count(),
            'archivedUsers' => 0, // No archived column in your table

            'cesoCount' => User::where('role', 'CESO')->count(),
            'facultyCount' => User::where('role', 'Faculty')->count(),
            'studentCount' => User::where('role', 'Student')->count(),
            'communityCount' => User::where('role', 'Community')->count(),
            'itCount' => User::where('role', 'IT')->count(),
        ]);
    }
}
