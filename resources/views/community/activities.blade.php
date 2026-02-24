@extends('layouts.community')

@section('title', 'Activities - Community Portal')

@section('content')

<div class="content-header">
    <h3><i class="fas fa-calendar-check me-2"></i> Available Activities</h3>
    <p class="text-muted">Find activities and join with an entry code</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
            <p class="mb-1">{{ $error }}</p>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Join Activity with Code Section -->
<div class="card mb-4 bg-light">
    <div class="card-body">
        <h5 class="card-title fw-bold mb-3">
            <i class="fas fa-key me-2"></i>Join Activity with Entry Code
        </h5>
        <p class="text-muted mb-3">Have an entry code? Enter it below to join an activity.</p>
        
        <form method="POST" action="{{ route('community.activities.join-with-code') }}" class="d-flex gap-2 flex-wrap">
            @csrf
            <div class="flex-grow-1" style="min-width: 250px;">
                <input type="text" 
                       name="entry_code" 
                       class="form-control @error('entry_code') is-invalid @enderror" 
                       placeholder="Enter 6-digit activity code (e.g., ABC123)" 
                       maxlength="6"
                       value="{{ old('entry_code') }}"
                       required>
                @error('entry_code')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Join Activity
            </button>
        </form>
    </div>
</div>

<!-- Browse Activities Section -->
@if($activities->count())
    <div class="mb-3">
        <h5 class="fw-bold">Browse Activities</h5>
        <small class="text-muted">View activity details or ask activity organizers for the entry code</small>
    </div>
    
    <div class="row g-3">
        @foreach($activities as $activity)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <span class="badge bg-info">{{ $activity->entry_code }}</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $activity->title }}</h5>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $activity->venue }}
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-calendar me-1"></i>{{ $activity->start_date?->format('M d, Y') }} - {{ $activity->end_date?->format('M d, Y') }}
                    </p>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="{{ route('community.activities.show', $activity->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-3">{{ $activities->links() }}</div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-info-circle fs-5"></i>
        <p class="mt-3 mb-0">No activities available at the moment. Please check back later!</p>
    </div>
@endif

@endsection
