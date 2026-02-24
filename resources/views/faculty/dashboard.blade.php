@extends('layouts.faculty')

@section('title', 'Dashboard - Faculty Portal')

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
                <h5 class="fw-bold mb-0">Activity Participation</h5>
            </div>
            <div class="card-body">
                <canvas id="participationChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Feedback Status</h5>
            </div>
            <div class="card-body">
                <canvas id="feedbackChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ANNOUNCEMENTS -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Announcements</h5>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @php
                    $recentAnnouncements = \App\Models\Announcement::published()
                        ->orderBy('published_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($recentAnnouncements->count())
                    <div class="list-group list-group-flush">
                        @foreach($recentAnnouncements as $announcement)
                        <a href="{{ route('announcements.show', $announcement->id) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $announcement->title }}</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($announcement->content, 100, '...') }}</p>
                                </div>
                                <small class="text-muted ms-2">{{ $announcement->published_at->format('M d') }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4">No announcements yet</p>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Participation Chart
    const participationCtx = document.getElementById('participationChart')?.getContext('2d');
    if (participationCtx) {
        new Chart(participationCtx, {
            type: 'bar',
            data: {
                labels: ['Available', 'Joined', 'Pending Feedback', 'Submitted Feedback'],
                datasets: [{
                    label: 'Activities',
                    data: [{{ $stats['availableActivities'] }}, {{ $stats['joinedActivities'] }}, {{ $stats['pendingFeedback'] }}, {{ $stats['submittedFeedback'] }}],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0'],
                    borderColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Feedback Status Chart
    const feedbackCtx = document.getElementById('feedbackChart')?.getContext('2d');
    if (feedbackCtx) {
        new Chart(feedbackCtx, {
            type: 'doughnut',
            data: {
                labels: ['Submitted Feedback', 'Pending Feedback'],
                datasets: [{
                    data: [{{ $stats['submittedFeedback'] }}, {{ $stats['pendingFeedback'] }}],
                    backgroundColor: ['#198754', '#ffc107'],
                    borderColor: ['#198754', '#ffc107'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
@endpush
