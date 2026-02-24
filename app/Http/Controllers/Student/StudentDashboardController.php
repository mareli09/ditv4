<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Student') {
            abort(403);
        }

        $user = auth()->user();
        $stats = [
            'availableActivities' => 5, // Placeholder
            'joinedActivities' => 3,    // Placeholder
            'pendingFeedback' => 1,     // Placeholder
            'submittedFeedback' => 2    // Placeholder
        ];

        return view('student.dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
