@extends('layouts.community')

@section('title', $announcement->title)

@section('content')
<div class="content-header">
    <div>
        <h3><i class="fas fa-bullhorn me-2"></i>{{ $announcement->title }}</h3>
        <p class="text-muted mb-0">Published {{ $announcement->published_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Announcements
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Announcement Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $announcement->published_at->format('M d, Y \a\t H:i A') }}
                        </small>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">
                            By {{ $announcement->createdBy->name ?? 'CESO' }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="announcement-content">
                    {{ $announcement->content }}
                </div>
            </div>

            <div class="card-footer bg-light border-top text-muted">
                <small>
                    Last updated {{ $announcement->updated_at->diffForHumans() }}
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Related Announcements -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0">
                <h6 class="fw-bold mb-0">Recent Announcements</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $relatedAnnouncements = \App\Models\Announcement::published()
                        ->where('id', '!=', $announcement->id)
                        ->orderBy('published_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($relatedAnnouncements->count())
                    <div class="list-group list-group-flush">
                        @foreach($relatedAnnouncements as $related)
                        <a href="{{ route('announcements.show', $related->id) }}" class="list-group-item list-group-item-action py-3">
                            <h6 class="mb-1 fw-bold">{{ Str::limit($related->title, 30) }}</h6>
                            <small class="text-muted">{{ $related->published_at->format('M d, Y') }}</small>
                        </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">No other announcements</p>
                @endif
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
