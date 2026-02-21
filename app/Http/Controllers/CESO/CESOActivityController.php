<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityFeedback;
use Illuminate\Http\Request;

class CESOActivityController extends Controller
{
    public function create()
    {
        if (auth()->user()->role !== 'CESO') {
            abort(403);
        }

        return view('ceso.create_activity');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'CESO') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'conducted_by' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'description' => 'nullable|string'
        ]);

        $activity = Activity::create(array_merge($validated, [
            'created_by' => auth()->id(),
            'attachments' => null
        ]));

        return redirect()->route('ceso.activities.show', $activity->id)->with('success', 'Activity created.');
    }

    public function index()
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $activities = Activity::orderBy('start_date','desc')->paginate(10);
        return view('ceso.activities', ['activities' => $activities]);
    }

    public function show(Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $activity->load('feedback.user');
        return view('ceso.activity_show', ['activity' => $activity]);
    }

    public function feedback(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'role' => 'nullable|string',
            'source' => 'nullable|string',
            'rating' => 'nullable|integer|min:0|max:5',
            'comment' => 'nullable|string'
        ]);

        $data['user_id'] = auth()->id() ?? null;
        $activity->feedback()->create($data);

        return back()->with('success', 'Thank you for your feedback.');
    }
}
