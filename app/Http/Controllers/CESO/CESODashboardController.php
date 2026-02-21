<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CESODashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'CESO') {
            abort(403);
        }

        $user = auth()->user();
        $stats = [
            'previousActivities' => 12,
            'ongoingActivities' => 3,
            'upcomingActivities' => 5
        ];

        return view('ceso.dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
