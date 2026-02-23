@extends('layouts.ceso')

@section('title', 'View Announcement - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">{{ $announcement->title }}</h3>
        <a href="{{ route('ceso.announcements.index') }}" class="btn btn-outline-secondary">Back to Announcements</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="row">
                        <div class="col">
                            <div>
                                <strong>Status:</strong>
                                @if($announcement->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @elseif($announcement->status === 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @else
                                    <span class="badge bg-warning">Archived</span>
                                @endif
                            </div>
                        </div>
                        <div class="col text-end">
                            <small class="text-muted">
                                Created {{ $announcement->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="announcement-content">
                        {{ $announcement->content }}
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        @if($announcement->published_at)
                            Published: {{ $announcement->published_at->format('M d, Y H:i') }}
                        @endif
                        @if($announcement->updatedBy)
                            | Last updated by {{ $announcement->updatedBy->name }} on {{ $announcement->updated_at->format('M d, Y H:i') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('ceso.announcements.edit', $announcement->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($announcement->status !== 'archived')
                            <form method="POST" action="{{ route('ceso.announcements.archive', $announcement->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Archive this announcement?')">
                                    <i class="fas fa-archive"></i> Archive
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('ceso.announcements.restore', $announcement->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Restore this announcement?')">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.announcement-content {
    line-height: 1.8;
    color: #333;
    font-size: 1rem;
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
@endsection
