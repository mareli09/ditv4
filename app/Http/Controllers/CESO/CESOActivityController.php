<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use OpenAI\Client;
use OpenAI\Laravel\Facades\OpenAI; 
use App\Services\SentimentService;

class CESOActivityController extends Controller
{
    public function create()
    {
        if (auth()->user()->role !== 'CESO') {
            abort(403);
        }

        // Get all users grouped by role for participant selection
        $faculty = User::where('role', 'Faculty')->get();
        $staff = User::where('role', 'IT')->orWhere('role', 'CESO')->get();
        $students = User::where('role', 'Student')->get();
        $community = User::where('role', 'Community')->get();

        return view('ceso.create_activity', compact('faculty', 'staff', 'students', 'community'));
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
            'description' => 'nullable|string',
            'invited_faculty_ids' => 'nullable|array',
            'invited_faculty_ids.*' => 'exists:users,id',
            'invited_staff_ids' => 'nullable|array',
            'invited_staff_ids.*' => 'exists:users,id',
            'invited_student_ids' => 'nullable|array',
            'invited_student_ids.*' => 'exists:users,id',
            'invited_community_ids' => 'nullable|array',
            'invited_community_ids.*' => 'exists:users,id',
            'invited_other_names' => 'nullable|array',
            'invited_other_names.*' => 'string|max:255',
        ]);

        $activity = Activity::create(array_merge(
            collect($validated)->except(['invited_faculty_ids', 'invited_staff_ids', 'invited_student_ids', 'invited_community_ids', 'invited_other_names'])->toArray(),
            ['created_by' => auth()->id(), 'attachments' => null]
        ));

        // Attach participants from database
        $participantMaps = [
            'invited_faculty_ids' => 'faculty',
            'invited_staff_ids' => 'staff',
            'invited_student_ids' => 'student',
            'invited_community_ids' => 'community'
        ];

        foreach ($participantMaps as $key => $type) {
            if (!empty($validated[$key])) {
                foreach ($validated[$key] as $userId) {
                    $activity->participants()->create([
                        'user_id' => $userId,
                        'participant_type' => $type
                    ]);
                }
            }
        }

        // Attach "other" participants (not in database)
        if (!empty($validated['invited_other_names'])) {
            foreach ($validated['invited_other_names'] as $name) {
                $activity->participants()->create([
                    'user_id' => null,
                    'participant_type' => 'other',
                    'name' => $name
                ]);
            }
        }

        return redirect()->route('ceso.activities.show', $activity->id)->with('success', 'Activity created.');
    }

    public function edit(Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        // Get users for selection and load existing participants
        $faculty = User::where('role', 'Faculty')->get();
        $staff = User::where('role', 'IT')->orWhere('role', 'CESO')->get();
        $students = User::where('role', 'Student')->get();
        $community = User::where('role', 'Community')->get();

        $activity->load('participants');

        return view('ceso.edit_activity', compact('activity', 'faculty', 'staff', 'students', 'community'));
    }

    public function update(Request $request, Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'conducted_by' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'description' => 'nullable|string',
            'invited_faculty_ids' => 'nullable|array',
            'invited_faculty_ids.*' => 'exists:users,id',
            'invited_staff_ids' => 'nullable|array',
            'invited_staff_ids.*' => 'exists:users,id',
            'invited_student_ids' => 'nullable|array',
            'invited_student_ids.*' => 'exists:users,id',
            'invited_community_ids' => 'nullable|array',
            'invited_community_ids.*' => 'exists:users,id',
            'invited_other_names' => 'nullable|array',
            'invited_other_names.*' => 'string|max:255',
        ]);

        // Update activity fields (don't change created_by)
        $activity->update(
            collect($validated)->except(['invited_faculty_ids', 'invited_staff_ids', 'invited_student_ids', 'invited_community_ids', 'invited_other_names'])->toArray()
        );

        // Remove existing participants and re-attach
        $activity->participants()->delete();

        $participantMaps = [
            'invited_faculty_ids' => 'faculty',
            'invited_staff_ids' => 'staff',
            'invited_student_ids' => 'student',
            'invited_community_ids' => 'community'
        ];

        foreach ($participantMaps as $key => $type) {
            if (!empty($validated[$key])) {
                foreach ($validated[$key] as $userId) {
                    $activity->participants()->create([
                        'user_id' => $userId,
                        'participant_type' => $type
                    ]);
                }
            }
        }

        if (!empty($validated['invited_other_names'])) {
            foreach ($validated['invited_other_names'] as $name) {
                $activity->participants()->create([
                    'user_id' => null,
                    'participant_type' => 'other',
                    'name' => $name
                ]);
            }
        }

        return redirect()->route('ceso.activities.show', $activity->id)->with('success', 'Activity updated.');
    }

    public function archive(Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $activity->update(['archived_at' => now()]);
        return redirect()->route('ceso.activities.index')->with('success', 'Activity archived successfully.');
    }

    public function restore(Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $activity->update(['archived_at' => null]);
        return redirect()->route('ceso.activities.archived')->with('success', 'Activity restored successfully.');
    }

    public function index()
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        // Show only non-archived activities in the main list
        $activities = Activity::whereNull('archived_at')->orderBy('start_date','desc')->paginate(10);
        return view('ceso.activities', ['activities' => $activities]);
    }

    public function archivedIndex()
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $activities = Activity::whereNotNull('archived_at')->orderBy('archived_at', 'desc')->paginate(10);
        return view('ceso.archived_activities', ['activities' => $activities]);
    }

    public function show(Activity $activity)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        // Temporary feedback data
        $feedback = [
            [
                'user' => 'John Doe',
                'role' => 'Student',
                'rating' => 5,
                'comment' => 'The activity was very informative and engaging.'
            ],
            [
                'user' => 'Jane Smith',
                'role' => 'Faculty',
                'rating' => 4,
                'comment' => 'Well-organized but could use more interactive sessions.'
            ],
            [
                'user' => 'Community Member',
                'role' => 'Community',
                'rating' => 5,
                'comment' => 'Great initiative for the community!'
            ]
        ];

        return view('ceso.activity_show', [
            'activity' => $activity,
            'feedback' => $feedback
        ]);
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

    public function analyzeFeedback(Activity $activity, SentimentService $sentimentService)
    {
        // Fetch feedback data
        $feedback = $activity->feedback()->pluck('comment')->toArray();

        // Analyze each feedback comment
        $sentiments = [];
        $sentimentCounts = ['Positive' => 0, 'Neutral' => 0, 'Negative' => 0];

        foreach ($feedback as $comment) {
            $sentiment = $sentimentService->analyze($comment);
            $sentiments[] = [
                'comment' => $comment,
                'sentiment' => $sentiment,
            ];

            // Increment sentiment counts
            if (isset($sentimentCounts[$sentiment])) {
                $sentimentCounts[$sentiment]++;
            }
        }

        // Pass analysis and sentiment counts to the view
        return view('ceso.activity_show', [
            'activity' => $activity,
            'sentiments' => $sentiments,
            'sentimentCounts' => $sentimentCounts,
        ]);
    }
}
