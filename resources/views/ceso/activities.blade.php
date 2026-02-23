@extends('layouts.ceso')

@section('title', 'Activities - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">All Activities</h3>
            <p class="text-muted small mb-0">Manage and monitor all CESO activities</p>
        </div>
        <div>
            <a href="{{ route('ceso.activities.archived') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-archive me-1"></i> Archived
            </a>
            <a href="{{ route('ceso.activities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Activity
            </a>
        </div>
    </div>

    {{-- SEARCH AND FILTER BAR --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by title, venue, or description..." value="{{ $search }}">
                </div>

                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ $sort_by === 'created_at' ? 'selected' : '' }}>Sort by Created Date</option>
                        <option value="start_date" {{ $sort_by === 'start_date' ? 'selected' : '' }}>Sort by Start Date</option>
                        <option value="title" {{ $sort_by === 'title' ? 'selected' : '' }}>Sort by Title</option>
                        <option value="venue" {{ $sort_by === 'venue' ? 'selected' : '' }}>Sort by Venue</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ $sort_order === 'desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="asc" {{ $sort_order === 'asc' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ACTIVITIES TABLE --}}
    @if($activities->count())
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Venue</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $act)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($act->title, 40) }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">{{ $act->venue ?? 'N/A' }}</span>
                            </td>
                            <td>
                                {{ $act->start_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $act->end_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td>
                                <small class="text-muted">{{ $act->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="gap-1 d-flex justify-content-center">
                                    <a href="{{ route('ceso.activities.show', $act->id) }}" class="btn btn-sm btn-info" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()?->role === 'CESO')
                                        <a href="{{ route('ceso.activities.edit', $act->id) }}" class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(is_null($act->archived_at))
                                            <form method="POST" action="{{ route('ceso.activities.archive', $act->id) }}" style="display:inline;" onsubmit="return confirm('Archive this activity?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Archive" data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <p class="text-muted small mb-0">
                Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} activities
            </p>
            <div>
                {{ $activities->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="fas fa-inbox fa-2x mb-3"></i><br>
            <strong>No activities found</strong><br>
            <small class="text-muted">Try adjusting your search criteria or create a new activity.</small>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

@endsection

    