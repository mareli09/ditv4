@extends('layouts.ceso')

@section('title', 'View Project')

@section('content')

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold">{{ $project->title }}</h3>
            <p class="text-muted mb-0">Project Details</p>
        </div>
        <div class="gap-2 d-flex">
            <a href="{{ route('ceso.projects.edit', $project) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('ceso.projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Project Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">DESCRIPTION</h6>
                    <p>{{ $project->description }}</p>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">CONDUCTED BY</h6>
                        <p class="fw-bold">{{ $project->conducted_by }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">TARGET AUDIENCE</h6>
                        <p class="fw-bold">{{ $project->target_audience }}</p>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">START DATE</h6>
                        <p class="fw-bold">{{ $project->start_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">END DATE</h6>
                        <p class="fw-bold">{{ $project->end_date->format('F d, Y') }}</p>
                    </div>
                </div>

                @if($project->remarks)
                    <hr>
                    <div>
                        <h6 class="text-muted small mb-1">REMARKS</h6>
                        <p>{{ $project->remarks }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Project Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted small mb-2">CURRENT STATUS</h6>
                    <span class="badge 
                        @if($project->status === 'Proposed') bg-info
                        @elseif($project->status === 'Ongoing') bg-warning
                        @else bg-success
                        @endif" 
                        style="font-size: 14px; padding: 8px 12px;">
                        {{ $project->status }}
                    </span>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted small mb-2">PROJECT DURATION</h6>
                    <p class="small mb-0">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $project->start_date->format('M d') }} - {{ $project->end_date->format('M d, Y') }}
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted small mb-2">CREATED</h6>
                    <p class="small mb-0">
                        <i class="fas fa-clock me-1"></i>
                        {{ $project->created_at->format('F d, Y H:i A') }}
                    </p>
                </div>

                <div>
                    <h6 class="text-muted small mb-2">LAST UPDATED</h6>
                    <p class="small mb-0">
                        <i class="fas fa-sync me-1"></i>
                        {{ $project->updated_at->format('F d, Y H:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
