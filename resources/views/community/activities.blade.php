@extends('layouts.community')

@section('title', 'Activities - Community Portal')

@section('content')

<div class="content-header">
    <h3><i class="fas fa-calendar-check me-2"></i> Available Activities</h3>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-muted">Activities list will be displayed here. Community members can browse and join available CESO activities.</p>
    </div>
</div>

<div class="row g-3">
    @foreach($activities as $activity)
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>{{ $activity->title }}</h5>
                <p class="text-muted">{{ $activity->venue }}</p>
                <p class="mb-0">{{ $activity->start_date?->format('M d, Y') }} - {{ $activity->end_date?->format('M d, Y') }}</p>
            </div>
            <div class="card-footer bg-transparent d-flex">
                <a href="{{ route('community.activities.show', $activity->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                <form method="POST" action="{{ route('community.activities.join', $activity->id) }}" class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Join</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-3">{{ $activities->links() }}</div>

@endsection
