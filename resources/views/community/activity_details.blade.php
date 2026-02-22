@extends('layouts.community')

@section('title', $activity->title . ' - Community Portal')

@section('content')
<div class="content-header">
    <h3>{{ $activity->title }}</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Venue:</strong> {{ $activity->venue }}</p>
        <p><strong>Start Date:</strong> {{ $activity->start_date?->format('M d, Y') }}</p>
        <p><strong>End Date:</strong> {{ $activity->end_date?->format('M d, Y') }}</p>
        <p>{{ $activity->description }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Submit Feedback</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('community.activities.feedback', $activity->id) }}">
            @csrf

            <div class="mb-3">
                <label for="rating" class="form-label">Rating (1-5)</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>
</div>
@endsection