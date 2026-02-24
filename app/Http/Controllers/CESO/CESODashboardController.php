<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Activity;
use App\Models\ActivityFeedback;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class CESODashboardController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        if (auth()->user()->role !== 'CESO') {
            abort(403);
        }

        $user = auth()->user();

        // Gather project statistics
        $projectStats = $this->getProjectStatistics();
        
        // Gather activity statistics
        $activityStats = $this->getActivityStatistics();
        
        // Get detailed project data for insights
        $projectData = $this->getProjectDetails();
        
        // Get detailed activity data for insights
        $activityData = $this->getActivityDetails();
        
        // Generate AI insights
        $aiInsights = $this->openAIService->generateDashboardInsights(
            $projectStats,
            $activityStats,
            $projectData,
            $activityData
        );

        return view('ceso.dashboard', [
            'user' => $user,
            'projectStats' => $projectStats,
            'activityStats' => $activityStats,
            'projects' => Project::with('users')->latest()->take(5)->get(),
            'recentActivities' => Activity::latest()->take(5)->get(),
            'aiInsights' => $aiInsights,
            'allProjects' => Project::all(),
            'allActivities' => Activity::all(),
            'allFeedback' => ActivityFeedback::latest()->take(10)->get()
        ]);
    }

    /**
     * Get project statistics
     */
    private function getProjectStatistics()
    {
        $projects = Project::all();

        return [
            'total' => $projects->count(),
            'active' => $projects->where('status', 'Ongoing')->count(),
            'completed' => $projects->where('status', 'Completed')->count(),
            'proposed' => $projects->where('status', 'Proposed')->count(),
            'totalMembers' => $projects->sum(function ($project) {
                return $project->users()->count();
            })
        ];
    }

    /**
     * Get activity statistics
     */
    private function getActivityStatistics()
    {
        $activities = Activity::withoutGlobalScopes()->get();
        $now = now();

        $upcoming = $activities->filter(function ($activity) use ($now) {
            return $activity->start_date && $activity->start_date > $now;
        })->count();

        $ongoing = $activities->filter(function ($activity) use ($now) {
            return $activity->start_date && 
                   $activity->start_date <= $now && 
                   (!$activity->end_date || $activity->end_date >= $now) &&
                   !$activity->archived_at;
        })->count();

        $completed = $activities->filter(function ($activity) {
            return $activity->archived_at || 
                   ($activity->end_date && $activity->end_date < now());
        })->count();

        $feedback = ActivityFeedback::all();
        $avgRating = $feedback->count() > 0 ? $feedback->avg('rating') : 0;

        return [
            'total' => $activities->count(),
            'upcoming' => $upcoming,
            'ongoing' => $ongoing,
            'completed' => $completed,
            'totalParticipants' => $activities->sum(function ($activity) {
                return $activity->participants()->count();
            }),
            'avgRating' => round($avgRating, 2),
            'totalFeedback' => $feedback->count()
        ];
    }

    /**
     * Get detailed project information
     */
    private function getProjectDetails()
    {
        return Project::all()
            ->map(function ($project) {
                return [
                    'title' => $project->title,
                    'status' => $project->status ?? 'Unknown',
                    'members' => $project->users()->count(),
                    'id' => $project->id
                ];
            })
            ->take(10)
            ->toArray();
    }

    /**
     * Get detailed activity information
     */
    private function getActivityDetails()
    {
        return Activity::withoutGlobalScopes()
            ->get()
            ->map(function ($activity) {
                $feedback = $activity->feedback;
                $avgRating = $feedback->count() > 0 ? $feedback->avg('rating') : 0;
                
                $now = now();
                $status = 'Completed';
                if ($activity->start_date && $activity->start_date > $now) {
                    $status = 'Upcoming';
                } elseif ($activity->start_date && $activity->start_date <= $now && 
                         (!$activity->end_date || $activity->end_date >= $now) &&
                         !$activity->archived_at) {
                    $status = 'Ongoing';
                }

                return [
                    'title' => $activity->title ?? 'Unknown Activity',
                    'status' => $status,
                    'participants' => $activity->participants()->count(),
                    'rating' => round($avgRating, 2),
                    'id' => $activity->id
                ];
            })
            ->take(10)
            ->toArray();
    }
}
