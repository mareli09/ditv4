<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Faculty') {
            abort(403);
        }

        $user = auth()->user();
        $stats = [
            'availableActivities' => 5, // Placeholder
            'joinedActivities' => 3,    // Placeholder
            'pendingFeedback' => 1,     // Placeholder
            'submittedFeedback' => 2    // Placeholder
        ];

        return view('faculty.dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
