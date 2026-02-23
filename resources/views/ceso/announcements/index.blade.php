@extends('layouts.ceso')

@section('title', 'Announcements - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Announcements</h3>
        <a href="{{ route('ceso.announcements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Announcement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('ceso.announcements.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
                <div class="flex-grow-1" style="min-width: 250px;">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search announcements..." value="{{ $search }}">
                </div>
                <div style="min-width: 150px;">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All</option>
                        <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ $status == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('ceso.announcements.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    @if($announcements->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($announcements as $announcement)
                    <tr>
                        <td>{{ $announcements->firstItem() + $loop->index }}</td>
                        <td class="fw-500">{{ $announcement->title }}</td>
                        <td>
                            @if($announcement->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif($announcement->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @else
                                <span class="badge bg-warning">Archived</span>
                            @endif
                        </td>
                        <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $announcement->published_at?->format('M d, Y H:i') ?? '—' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('ceso.announcements.show', $announcement->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="View">
                                    👁
                                </a>
                                <a href="{{ route('ceso.announcements.edit', $announcement->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" data-bs-title="Edit">
                                    ✎
                                </a>
                                @if($announcement->status !== 'archived')
                                    <form method="POST" action="{{ route('ceso.announcements.archive', $announcement->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Archive" onclick="return confirm('Archive this announcement?')">
                                            📦
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('ceso.announcements.restore', $announcement->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Restore" onclick="return confirm('Restore this announcement?')">
                                            ↩
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Showing {{ $announcements->firstItem() }} to {{ $announcements->lastItem() }} of {{ $announcements->total() }} announcements</small>
            <div>{{ $announcements->links() }}</div>
        </div>
    @else
        <div class="alert alert-info text-center py-4">
            <i class="fas fa-info-circle"></i> No announcements found. @if($search)<a href="{{ route('ceso.announcements.index') }}">Clear search</a>@endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection
