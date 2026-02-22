@extends('layouts.ceso')

@section('title', 'Community Feedback')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Submit Your Feedback</h3>

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

    <form method="POST" action="{{ route('community.feedback.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="activity_id" class="form-label">Activity</label>
            <select name="activity_id" id="activity_id" class="form-control" required>
                @foreach(App\Models\Activity::all() as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="source" class="form-label">Your Name</label>
            <input type="text" name="source" id="source" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Your Role</label>
            <input type="text" name="role" id="role" class="form-control">
        </div>

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
@endsection