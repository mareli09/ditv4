@extends('layouts.ceso')

@section('title', 'Archived Activities - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Archived Activities</h3>
        <a href="{{ route('ceso.activities.index') }}" class="btn btn-outline-secondary">Back to Activities</a>
    </div>

    @if($activities->count())
        <div class="row g-3">
            @foreach($activities as $act)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>{{ $act->title }}</h5>
                        <p class="text-muted">{{ $act->venue }}</p>
                        <p class="mb-0">Archived: {{ $act->archived_at?->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="card-footer bg-transparent d-flex">
                        <a href="{{ route('ceso.activities.show', $act->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <form method="POST" action="{{ route('ceso.activities.restore', $act->id) }}" class="ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this activity?')">Restore</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-3">{{ $activities->links() }}</div>
    @else
        <div class="alert alert-info">No archived activities</div>
    @endif
</div>
@endsection
