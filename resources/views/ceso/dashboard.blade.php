@extends('layouts.ceso')

@section('title', 'CESO Staff Dashboard')

@section('content')

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm border-left-primary" style="border-left: 4px solid #0d6efd;">
                <div class="card-body p-3 p-lg-4">
                    <h3 class="card-title mb-0 fs-5 fs-lg-3">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-muted mb-0 small">CESO Dashboard - {{ now()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <!-- Total Projects -->
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body p-2 p-lg-3">
                    <div class="display-6 display-lg-4 text-primary fw-bold">{{ $projectStats['total'] }}</div>
                    <h6 class="text-muted fs-7 fs-lg-6">Total Projects</h6>
                    <small class="text-success">{{ $projectStats['active'] }} Active</small>
                </div>
            </div>
        </div>

        <!-- Total Activities -->
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body p-2 p-lg-3">
                    <div class="display-6 display-lg-4 text-success fw-bold">{{ $activityStats['total'] }}</div>
                    <h6 class="text-muted fs-7 fs-lg-6">Total Activities</h6>
                    <small class="text-info">{{ $activityStats['ongoing'] }} Ongoing</small>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body p-2 p-lg-3">
                    <div class="display-6 display-lg-4 text-info fw-bold">{{ $projectStats['totalMembers'] }}</div>
                    <h6 class="text-muted fs-7 fs-lg-6">Team Members</h6>
                    <small class="text-secondary">Across {{ $projectStats['total'] }} projects</small>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body p-2 p-lg-3">
                    <div class="display-6 display-lg-4 text-warning fw-bold">{{ $activityStats['avgRating'] }}</div>
                    <h6 class="text-muted fs-7 fs-lg-6">Avg. Rating</h6>
                    <small class="text-secondary">From {{ $activityStats['totalFeedback'] }} feedback</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Project & Activity Overview Row -->
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <!-- Project Status Breakdown -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">Project Overview</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    <div class="row text-center mb-3 g-2">
                        <div class="col-4">
                            <h3 class="text-primary fw-bold fs-5 fs-lg-3">{{ $projectStats['proposed'] }}</h3>
                            <small class="text-muted d-block">Proposed</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success fw-bold fs-5 fs-lg-3">{{ $projectStats['active'] }}</h3>
                            <small class="text-muted d-block">Ongoing</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-info fw-bold fs-5 fs-lg-3">{{ $projectStats['completed'] }}</h3>
                            <small class="text-muted d-block">Completed</small>
                        </div>
                    </div>
                    <hr>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $projectStats['total'] > 0 ? ($projectStats['active'] / $projectStats['total'] * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $projectStats['active'] }}/{{ $projectStats['total'] }} ({{ $projectStats['total'] > 0 ? round($projectStats['active'] / $projectStats['total'] * 100) : 0 }}%) active</small>
                </div>
            </div>
        </div>

        <!-- Activity Status Breakdown -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">Activity Overview</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    <div class="row text-center mb-3 g-2">
                        <div class="col-4">
                            <h3 class="text-warning fw-bold fs-5 fs-lg-3">{{ $activityStats['upcoming'] }}</h3>
                            <small class="text-muted d-block">Upcoming</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success fw-bold fs-5 fs-lg-3">{{ $activityStats['ongoing'] }}</h3>
                            <small class="text-muted d-block">Ongoing</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-secondary fw-bold fs-5 fs-lg-3">{{ $activityStats['completed'] }}</h3>
                            <small class="text-muted d-block">Completed</small>
                        </div>
                    </div>
                    <hr>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ $activityStats['total'] > 0 ? ($activityStats['ongoing'] / $activityStats['total'] * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $activityStats['ongoing'] }}/{{ $activityStats['total'] }} ({{ $activityStats['total'] > 0 ? round($activityStats['ongoing'] / $activityStats['total'] * 100) : 0 }}%) ongoing</small>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights Section -->
    @if($aiInsights)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <!-- Summary -->
        <div class="col-12">
            <div class="card shadow-sm border-left-info" style="border-left: 4px solid #0dcaf0;">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">🤖 AI Insights & Analysis</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    <p class="lead small fs-md-6">{{ $aiInsights['summary'] ?? 'AI analysis is being generated...' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Row -->
    @if($aiInsights['alerts'] && count($aiInsights['alerts']) > 0)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm border-left-danger" style="border-left: 4px solid #dc3545;">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">⚠️ Alerts & Concerns</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    @foreach($aiInsights['alerts'] as $alert)
                    <div class="alert alert-warning mb-2" role="alert">
                        <strong>•</strong> <span class="small">{{ $alert }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Highlights Row -->
    @if($aiInsights['highlights'] && count($aiInsights['highlights']) > 0)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm border-left-success" style="border-left: 4px solid #198754;">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">✨ Highlights & Achievements</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    @foreach($aiInsights['highlights'] as $highlight)
                    <div class="alert alert-success mb-2" role="alert">
                        <strong>•</strong> <span class="small">{{ $highlight }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Improvement Areas Row -->
    @if($aiInsights['improvements'] && count($aiInsights['improvements']) > 0)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">📊 Areas for Improvement</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    @foreach($aiInsights['improvements'] as $improvement)
                    <div class="mb-3">
                        <span class="badge bg-secondary small">Improvement</span>
                        <p class="mt-2 mb-0 small">{{ $improvement }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recommendations Row -->
    @if($aiInsights['suggestions'] && count($aiInsights['suggestions']) > 0)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm border-left-primary" style="border-left: 4px solid #0d6efd;">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">💡 Recommended Actions</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    <ol class="mb-0 ps-3">
                        @foreach($aiInsights['suggestions'] as $suggestion)
                        <li class="mb-2 small">{{ $suggestion }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Next Steps Row -->
    @if($aiInsights['nextSteps'] && count($aiInsights['nextSteps']) > 0)
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">🎯 Next Steps</h5>
                </div>
                <div class="card-body p-3 p-lg-4">
                    @foreach($aiInsights['nextSteps'] as $step)
                    <div class="d-flex mb-3 gap-2">
                        <span class="badge bg-info flex-shrink-0" style="margin-top: 2px;">→</span>
                        <span class="small">{{ $step }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    <!-- Recent Projects -->
    <div class="row g-2 g-lg-4 mb-3 mb-lg-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">📋 Recent Projects</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($projects as $project)
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between flex-wrap gap-2">
                            <h6 class="mb-1 small">{{ $project->title ?? 'Unknown' }}</h6>
                            <span class="badge flex-shrink-0 bg-{{ $project->status === 'Ongoing' ? 'success' : ($project->status === 'Completed' ? 'secondary' : 'warning') }}">
                                {{ $project->status ?? 'Unknown' }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $project->users()->count() }} members</small>
                    </a>
                    @empty
                    <div class="list-group-item p-3">
                        <p class="text-muted mb-0 small">No projects yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">🎉 Recent Activities</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between flex-wrap gap-2">
                            <h6 class="mb-1 small">{{ $activity->title ?? 'Unknown' }}</h6>
                            <span class="badge flex-shrink-0 bg-info">{{ $activity->participants()->count() }} participants</span>
                        </div>
                        <small class="text-muted">{{ $activity->start_date ? $activity->start_date->format('M d, Y') : 'No date' }}</small>
                    </a>
                    @empty
                    <div class="list-group-item p-3">
                        <p class="text-muted mb-0 small">No activities yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Feedback -->
    <div class="row g-2 g-lg-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light p-3 p-lg-4">
                    <h5 class="mb-0 fs-6">💬 Recent Feedback</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="small">Activity</th>
                                <th class="small">Participant</th>
                                <th class="small">Rating</th>
                                <th class="small">Comment</th>
                                <th class="small">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allFeedback as $feedback)
                            <tr>
                                <td class="small">{{ Str::limit($feedback->activity->title ?? 'Unknown', 20) }}</td>
                                <td class="small">{{ Str::limit($feedback->user->name ?? 'Anonymous', 15) }}</td>
                                <td>
                                    <span class="badge bg-{{ $feedback->rating >= 4 ? 'success' : ($feedback->rating >= 3 ? 'warning' : 'danger') }} small">
                                        {{ $feedback->rating }} / 5
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ Str::limit($feedback->comment ?? 'No comment', 30) }}</small></td>
                                <td><small>{{ $feedback->created_at->format('M d') }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted small py-3">No feedback received yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection