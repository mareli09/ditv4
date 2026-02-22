@extends('layouts.ceso')

@section('title', 'Activities - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">All Activities</h3>
        <div>
            <a href="{{ route('ceso.activities.archived') }}" class="btn btn-outline-secondary me-2">Archived</a>
            <a href="{{ route('ceso.activities.create') }}" class="btn btn-primary">Create Activity</a>
        </div>
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
                    <div class="card-footer bg-transparent d-flex">
                        <a href="{{ route('ceso.activities.show', $act->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @if(auth()->user()?->role === 'CESO')
                            <a href="{{ route('ceso.activities.edit', $act->id) }}" class="btn btn-sm btn-outline-secondary ms-2">Edit</a>
                            @if(is_null($act->archived_at))
                                <form method="POST" action="{{ route('ceso.activities.archive', $act->id) }}" class="ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Archive this activity?')">Archive</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('ceso.activities.restore', $act->id) }}" class="ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this activity?')">Restore</button>
                                </form>
                            @endif
                        @endif
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

    