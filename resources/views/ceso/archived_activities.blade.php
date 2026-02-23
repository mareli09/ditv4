@extends('layouts.ceso')

@section('title', 'Archived Activities - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Archived Activities</h3>
        <a href="{{ route('ceso.activities.index') }}" class="btn btn-outline-secondary">Back to Activities</a>
    </div>

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('ceso.activities.archived') }}" class="d-flex gap-2 flex-wrap align-items-end">
                <div class="flex-grow-1" style="min-width: 250px;">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search by title, venue, description..." value="{{ $search }}">
                </div>
                <div style="min-width: 150px;">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select id="sort_by" name="sort_by" class="form-select">
                        <option value="archived_at" {{ $sort_by == 'archived_at' ? 'selected' : '' }}>Archived Date</option>
                        <option value="start_date" {{ $sort_by == 'start_date' ? 'selected' : '' }}>Start Date</option>
                        <option value="title" {{ $sort_by == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="venue" {{ $sort_by == 'venue' ? 'selected' : '' }}>Venue</option>
                    </select>
                </div>
                <div style="min-width: 120px;">
                    <label for="sort_order" class="form-label">Order</label>
                    <select id="sort_order" name="sort_order" class="form-select">
                        <option value="desc" {{ $sort_order == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ $sort_order == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('ceso.activities.archived') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    @if($activities->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Archived</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $act)
                    <tr>
                        <td class="fw-500">{{ $act->title }}</td>
                        <td>{{ $act->venue }}</td>
                        <td>{{ $act->start_date?->format('M d, Y') }}</td>
                        <td>{{ $act->end_date?->format('M d, Y') }}</td>
                        <td>{{ $act->archived_at?->format('M d, Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('ceso.activities.show', $act->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="View Details">👁</a>
                                <form method="POST" action="{{ route('ceso.activities.restore', $act->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" data-bs-title="Restore Activity" onclick="return confirm('Restore this activity?')">↩</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
            <small class="text-muted">Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} archived activities</small>
            <div>{{ $activities->links() }}</div>
        </div>
    @else
        <div class="alert alert-info text-center py-4">
            <i class="bi bi-inbox"></i> No archived activities found. @if($search)<a href="{{ route('ceso.activities.archived') }}">Clear search</a>@endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection
