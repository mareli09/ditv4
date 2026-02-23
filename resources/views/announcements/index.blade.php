@extends('layouts.community')

@section('title', 'Announcements')

@section('content')
<div class="content-header">
    <div>
        <h3><i class="fas fa-bullhorn me-2"></i>Announcements</h3>
        <p class="text-muted mb-0">Stay informed about important updates and news</p>
    </div>
</div>

<!-- Search Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('announcements.index') }}" class="d-flex gap-2 flex-wrap">
            <div class="flex-grow-1" style="min-width: 250px;">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search announcements..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($search)
                <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

@if($announcements->count())
    <div class="row g-4">
        @foreach($announcements as $announcement)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 announcement-card">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ $announcement->published_at->format('M d, Y') }}
                    </small>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2">{{ $announcement->title }}</h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($announcement->content, 120, '...') }}
                    </p>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-primary btn-sm w-100">
                        Read More <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $announcements->links() }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-info-circle fs-5"></i>
        <p class="mt-3">No announcements found. @if($search)<a href="{{ route('announcements.index') }}">Clear search</a>@endif</p>
    </div>
@endif

<style>
.announcement-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.announcement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
