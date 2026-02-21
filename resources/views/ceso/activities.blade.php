@extends('layouts.ceso')

@section('title', 'Activities - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">All Activities</h3>
        <a href="{{ route('ceso.activities.create') }}" class="btn btn-primary">Create Activity</a>
    </div>

    @if($activities->count())
        <div class="row g-3">
            @foreach($activities as $act)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>{{ $act->title }}</h5>
                        <p class="text-muted">{{ $act->venue }}</p>
                        <p class="mb-0">{{ $act->start_date?->format('M d, Y') }} - {{ $act->end_date?->format('M d, Y') }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('ceso.activities.show', $act->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-3">{{ $activities->links() }}</div>
    @else
        <div class="alert alert-info">No activities found</div>
    @endif
</div>
@endsection