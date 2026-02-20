@extends('layouts.community')

@section('title', 'Dashboard - Community Portal')

@section('content')

<div class="content-header">
    <div>
        <h3><i class="fas fa-chart-pie me-2"></i> Dashboard</h3>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->first_name }}!</p>
    </div>
</div>

<!-- STATS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Available Activities</h6>
                <h3>{{ $stats['availableActivities'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Joined Activities</h6>
                <h3>{{ $stats['joinedActivities'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Pending Feedback</h6>
                <h3>{{ $stats['pendingFeedback'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Feedback Submitted</h6>
                <h3>{{ $stats['submittedFeedback'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Participation Overview</h5>
            </div>
            <div class="card-body">
                <div class="chart-placeholder">
                    <i class="fas fa-chart-line me-2"></i> Chart: Activities Joined Over Time
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Feedback Status</h5>
            </div>
            <div class="card-body">
                <div class="chart-placeholder">
                    <i class="fas fa-chart-bar me-2"></i> Chart: Submitted vs Pending Feedback
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
