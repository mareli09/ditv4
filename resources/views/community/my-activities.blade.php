@extends('layouts.community')

@section('title', 'My Participation - Community Portal')

@section('content')

<div class="content-header">
    <h3><i class="fas fa-user-check me-2"></i> My Participation</h3>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-muted">Your joined activities and participation history will be displayed here.</p>
    </div>
</div>

<div class="row g-3">
    @forelse($activities as $activity)
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>{{ $activity->title }}</h5>
                <p class="text-muted">{{ $activity->venue }}</p>
                <p class="mb-0">{{ $activity->start_date?->format('M d, Y') }} - {{ $activity->end_date?->format('M d, Y') }}</p>

                @if($activity->feedback->where('user_id', auth()->id())->isEmpty())
                <form method="POST" action="{{ route('community.activities.feedback', $activity->id) }}" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label for="rating-{{ $activity->id }}" class="form-label">Rating (1-5)</label>
                        <input type="number" name="rating" id="rating-{{ $activity->id }}" class="form-control" min="1" max="5" required>
                    </div>

                    <div class="mb-3">
                        <label for="comment-{{ $activity->id }}" class="form-label">Comment</label>
                        <textarea name="comment" id="comment-{{ $activity->id }}" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </form>
                @else
                <p class="text-success mt-3">Feedback submitted. Thank you!</p>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">You have not joined any activities yet.</div>
    </div>
    @endforelse
</div>

@endsection
