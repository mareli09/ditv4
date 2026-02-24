<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Staff') {
            abort(403);
        }

        $user = auth()->user();
        $stats = [
            'availableActivities' => 5, // Placeholder
            'joinedActivities' => 3,    // Placeholder
            'pendingFeedback' => 1,     // Placeholder
            'submittedFeedback' => 2    // Placeholder
        ];

        return view('staff.dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
