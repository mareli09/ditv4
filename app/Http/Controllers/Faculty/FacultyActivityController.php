<?php

namespace App\Http\Controllers\Faculty;

use App\Models\Activity;
use App\Models\ActivityFeedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacultyActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fetch activities where the user has not submitted feedback
        $activities = Activity::whereDoesntHave('feedback', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNull('archived_at')->paginate(10);

        return view('faculty.activities', compact('activities'));
    }

    public function join(Activity $activity)
    {
        $user = auth()->user();
        if ($activity->participants()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['You have already joined this activity.']);
        }

        $activity->participants()->create([
            'user_id' => $user->id,
            'participant_type' => 'faculty',
        ]);

        return back()->with('success', 'You have successfully joined the activity.');
    }

    public function joinWithCode(Request $request)
    {
        $validated = $request->validate([
            'entry_code' => 'required|string|size:6',
        ]);

        $activity = Activity::where('entry_code', strtoupper($validated['entry_code']))
                            ->active()
                            ->first();

        if (!$activity) {
            return back()->withErrors(['Invalid entry code. Please check and try again.']);
        }

        $user = auth()->user();

        if ($activity->participants()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['You have already joined this activity.']);
        }

        $activity->participants()->create([
            'user_id' => $user->id,
            'participant_type' => 'faculty',
        ]);

        return redirect()->route('faculty.activities.show', $activity->id)
                        ->with('success', 'You have successfully joined the activity!');
    }

    public function show(Activity $activity)
    {
        return view('faculty.activity_details', compact('activity'));
    }

    public function submitFeedback(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        if ($activity->feedback()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['You have already submitted feedback for this activity.']);
        }

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
        $activities = $user->joinedActivities()->paginate(10);

        return view('faculty.my-activities', compact('activities'));
    }
}
