<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityFeedback;
use Illuminate\Http\Request;

class CommunityActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fetch activities where the user has not submitted feedback
        $activities = Activity::whereDoesntHave('feedback', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNull('archived_at')->paginate(10);

        return view('community.activities', compact('activities'));
    }

    public function join(Activity $activity)
    {
        // Check if the user has already joined
        $user = auth()->user();
        if ($activity->participants()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['You have already joined this activity.']);
        }

        // Add the user as a participant
        $activity->participants()->create([
            'user_id' => $user->id,
            'participant_type' => 'community',
        ]);

        return back()->with('success', 'You have successfully joined the activity.');
    }

    public function show(Activity $activity)
    {
        return view('community.activity_details', compact('activity'));
    }

    public function submitFeedback(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check if the user has already submitted feedback
        $user = auth()->user();
        if ($activity->feedback()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['You have already submitted feedback for this activity.']);
        }

        // Save feedback
        $activity->feedback()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    public function myActivities()
    {
        $user = auth()->user();
        $activities = $user->joinedActivities()->with('feedback')->get();

        return view('community.my-activities', compact('activities'));
    }
}