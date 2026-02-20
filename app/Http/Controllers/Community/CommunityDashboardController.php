<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunityDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Community') {
            abort(403);
        }

        $user = auth()->user();
        $stats = [
            'availableActivities' => 5, // Placeholder
            'joinedActivities' => 3,    // Placeholder
            'pendingFeedback' => 1,     // Placeholder
            'submittedFeedback' => 2    // Placeholder
        ];

        return view('community.dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
