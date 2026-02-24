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
            [
                'created_by' => auth()->id(), 
                'attachments' => null,
                'entry_code' => Activity::generateEntryCode(),
                'requires_entry_code' => true
            ]
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

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $query = Activity::whereNull('archived_at');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('venue', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // Sorting functionality
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if (in_array($sortBy, ['title', 'venue', 'start_date', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $activities = $query->paginate(15, ['*'], 'page', $request->input('page', 1));

        return view('ceso.activities', [
            'activities' => $activities,
            'search' => $request->input('search', ''),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);
    }

    public function archivedIndex(Request $request)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $search = $request->input('search');
        $sort_by = $request->input('sort_by', 'archived_at');
        $sort_order = $request->input('sort_order', 'desc');

        // Validate sort parameters to prevent SQL injection
        $allowed_sorts = ['title', 'venue', 'archived_at', 'start_date'];
        if (!in_array($sort_by, $allowed_sorts)) {
            $sort_by = 'archived_at';
        }
        if (!in_array($sort_order, ['asc', 'desc'])) {
            $sort_order = 'desc';
        }

        $query = Activity::whereNotNull('archived_at');

        if ($search) {
            $query = $query
                ->where('title', 'like', "%$search%")
                ->orWhere('venue', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        $activities = $query->orderBy($sort_by, $sort_order)->paginate(15)->appends(request()->query());
        return view('ceso.archived_activities', [
            'activities' => $activities,
            'search' => $search,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order
        ]);
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
        $feedbackWithRatings = $activity->feedback;

        // Analyze each feedback comment
        $sentiments = [];
        $sentimentCounts = ['Positive' => 0, 'Neutral' => 0, 'Negative' => 0];
        $ratings = [];

        foreach ($feedback as $comment) {
            $sentiment = $sentimentService->analyze($comment) ?? 'Neutral'; // Default to Neutral if analysis fails
            $sentiments[] = [
                'comment' => $comment,
                'sentiment' => $sentiment,
            ];

            // Increment sentiment counts
            if (isset($sentimentCounts[$sentiment])) {
                $sentimentCounts[$sentiment]++;
            }
        }

        // Collect ratings
        foreach ($feedbackWithRatings as $fb) {
            if ($fb->rating) {
                $ratings[] = $fb->rating;
            }
        }

        // Generate analysis summary
        $totalFeedback = array_sum($sentimentCounts);
        $analysis = $totalFeedback > 0
            ? sprintf(
                'Out of %d feedback comments, %d were positive, %d were neutral, and %d were negative.',
                $totalFeedback,
                $sentimentCounts['Positive'],
                $sentimentCounts['Neutral'],
                $sentimentCounts['Negative']
            )
            : 'No feedback available for sentiment analysis.';

        // Generate decision support insights
        $insights = $this->generateDecisionSupport($activity, $sentimentCounts, $ratings, $feedbackWithRatings);

        // Pass analysis and sentiment counts to the view
        return view('ceso.activity_show', [
            'activity' => $activity,
            'sentiments' => $sentiments,
            'sentimentCounts' => $sentimentCounts,
            'analysis' => $analysis,
            'insights' => $insights,
            'averageRating' => !empty($ratings) ? round(array_sum($ratings) / count($ratings), 2) : null,
        ]);
    }

    private function generateDecisionSupport(Activity $activity, $sentimentCounts, $ratings, $feedbackWithRatings)
    {
        $totalFeedback = array_sum($sentimentCounts);
        $insights = [
            'overall_performance' => 'N/A',
            'effectiveness_score' => 0,
            'key_findings' => [],
            'recommendations' => [],
            'sentiment_distribution' => [],
        ];

        if ($totalFeedback === 0) {
            $insights['key_findings'][] = 'No feedback has been collected yet.';
            return $insights;
        }

        // Calculate sentiment percentages
        $positivePercent = round(($sentimentCounts['Positive'] / $totalFeedback) * 100);
        $neutralPercent = round(($sentimentCounts['Neutral'] / $totalFeedback) * 100);
        $negativePercent = round(($sentimentCounts['Negative'] / $totalFeedback) * 100);

        $insights['sentiment_distribution'] = [
            'positive' => $positivePercent,
            'neutral' => $neutralPercent,
            'negative' => $negativePercent,
        ];

        // Calculate effectiveness score (0-100)
        $effectivenessScore = ($sentimentCounts['Positive'] * 100 + $sentimentCounts['Neutral'] * 50) / max($totalFeedback, 1);
        $insights['effectiveness_score'] = round($effectivenessScore);

        // Determine overall performance
        if ($effectivenessScore >= 80) {
            $insights['overall_performance'] = 'Excellent';
        } elseif ($effectivenessScore >= 60) {
            $insights['overall_performance'] = 'Good';
        } elseif ($effectivenessScore >= 40) {
            $insights['overall_performance'] = 'Fair';
        } else {
            $insights['overall_performance'] = 'Needs Improvement';
        }

        // Key findings
        $insights['key_findings'][] = "$positivePercent% of feedback was positive, suggesting good reception.";
        
        if ($negativePercent > 30) {
            $insights['key_findings'][] = "Significant negative feedback ($negativePercent%) indicates areas needing attention.";
        }

        if (!empty($ratings)) {
            $avgRating = array_sum($ratings) / count($ratings);
            $insights['key_findings'][] = "Average rating: " . round($avgRating, 1) . "/5 from " . count($ratings) . " respondents.";
        }

        if ($sentimentCounts['Neutral'] > $sentimentCounts['Positive']) {
            $insights['key_findings'][] = "Many neutral responses suggest mixed or lukewarm reception. Consider clarifying objectives.";
        }

        // Extract common issues from negative feedback
        $negativeComments = $feedbackWithRatings->where('rating', '<=', 2)->pluck('comment')->toArray();
        if (!empty($negativeComments)) {
            $commonIssues = $this->extractCommonThemes($negativeComments);
            if (!empty($commonIssues)) {
                $insights['key_findings'][] = "Common concerns: " . implode(", ", $commonIssues) . ".";
            }
        }

        // Recommendations
        if ($effectivenessScore < 60) {
            $insights['recommendations'][] = "📌 Review activity structure and content delivery to improve participant engagement.";
            $insights['recommendations'][] = "📌 Consider gathering more detailed feedback to understand specific pain points.";
        }

        if ($negativePercent >= 20) {
            $insights['recommendations'][] = "📌 Address key concerns raised in negative feedback before hosting similar activities.";
            $insights['recommendations'][] = "📌 Conduct a post-activity meeting to identify and resolve persistent issues.";
        }

        if ($positivePercent >= 70) {
            $insights['recommendations'][] = "✓ Strong positive feedback indicates successful execution. Replicate this approach in future activities.";
            $insights['recommendations'][] = "✓ Consider using this activity as a model for similar training initiatives.";
        }

        if (empty($ratings) || count($ratings) < ($totalFeedback / 2)) {
            $insights['recommendations'][] = "📌 Encourage more respondents to provide ratings for better quantitative assessment.";
        }

        if ($sentimentCounts['Positive'] > 0 && $sentimentCounts['Negative'] > 0) {
            $insights['recommendations'][] = "📌 Segment feedback by participant type (faculty, students, community) to tailor future improvements.";
        }

        // Add specific improvements based on participant segmentation
        $participantImprovements = $this->generateParticipantSpecificImprovements($feedbackWithRatings);
        if (!empty($participantImprovements)) {
            $insights['participant_improvements'] = $participantImprovements;
        }

        return $insights;
    }

    private function generateParticipantSpecificImprovements($feedbackWithRatings)
    {
        $feedbackByRole = $feedbackWithRatings->groupBy('role');
        $improvements = [];

        foreach ($feedbackByRole as $role => $feedbacks) {
            $lowRatingFeedback = $feedbacks->filter(function ($f) { return $f->rating <= 2; });
            
            if ($lowRatingFeedback->isEmpty()) {
                continue;
            }

            $comments = $lowRatingFeedback->pluck('comment')->toArray();
            $keywords = $this->extractCommonThemes($comments, 5);

            $specificImprovements = [];

            // Faculty-specific improvements
            if ($role === 'Faculty' || $role === 'faculty') {
                $specificImprovements[] = "Increase academic depth or research integration in content";
                $specificImprovements[] = "Provide pre-activity materials or orientation for better preparation";
                $specificImprovements[] = "Allow more time for discussion or Q&A sessions";
                $specificImprovements[] = "Offer continuing education credits or certificates of participation";
            }

            // Student-specific improvements
            elseif ($role === 'Student' || $role === 'student') {
                $specificImprovements[] = "Make content more interactive with group activities or workshops";
                $specificImprovements[] = "Improve venue accessibility (timing, location, transportation)";
                $specificImprovements[] = "Provide practical skills applicable to internships or career";
                $specificImprovements[] = "Offer refreshments or extend break times";
            }

            // Staff-specific improvements
            elseif ($role === 'Staff' || $role === 'staff') {
                $specificImprovements[] = "Better coordinate with departmental schedules to minimize conflicts";
                $specificImprovements[] = "Provide clear communication and expectations beforehand";
                $specificImprovements[] = "Include administrative or operational value in the activity";
                $specificImprovements[] = "Recognize staff contributions or provide incentives";
            }

            // Community-specific improvements
            elseif ($role === 'Community' || $role === 'community') {
                $specificImprovements[] = "Simplify technical jargon for general audience understanding";
                $specificImprovements[] = "Provide transportation assistance or accessible venue";
                $specificImprovements[] = "Offer practical takeaways they can implement immediately";
                $specificImprovements[] = "Include social elements to build community connections";
            }

            if (!empty($specificImprovements) && !empty($keywords)) {
                $improvements[$role] = [
                    'low_rating_count' => $lowRatingFeedback->count(),
                    'total_feedback' => $feedbacks->count(),
                    'common_issues' => $keywords,
                    'specific_improvements' => array_slice($specificImprovements, 0, 3),
                ];
            }
        }

        return $improvements;
    }

    private function extractCommonThemes($comments, $limit = 3)
    {
        $keywords = [];
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'is', 'was', 'be', 'were'];

        foreach ($comments as $comment) {
            $words = str_word_count(strtolower($comment), 1);
            foreach ($words as $word) {
                $word = trim($word, '.,!?;:');
                if (strlen($word) > 3 && !in_array($word, $stopWords)) {
                    $keywords[$word] = ($keywords[$word] ?? 0) + 1;
                }
            }
        }

        arsort($keywords);
        return array_slice(array_keys($keywords), 0, $limit);
    }

    public function communityFeedbackForm()
    {
        return view('community.feedback_form');
    }

    public function submitCommunityFeedback(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'role' => 'nullable|string',
            'source' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check if the user has already submitted feedback for this activity
        $existingFeedback = ActivityFeedback::where('activity_id', $validated['activity_id'])
            ->where('source', $validated['source'])
            ->first();

        if ($existingFeedback) {
            return back()->withErrors(['You have already submitted feedback for this activity.']);
        }

        // Save feedback
        ActivityFeedback::create($validated);

        return redirect()->route('community.feedback.form')->with('success', 'Thank you for your feedback!');
    }

    public function explainSentiment(Activity $activity, ActivityFeedback $feedback, SentimentService $sentimentService)
    {
        // Use the SentimentService to generate an explanation
        $explanation = $sentimentService->explain($feedback->comment);

        return response()->json([
            'explanation' => $explanation,
        ]);
    }
}
