@extends('layouts.ceso')

@section('title', 'Archived Projects')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Archived Projects</h3>
        <p class="text-muted mb-0">View and restore archived projects</p>
    </div>
    <a href="{{ route('ceso.projects.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Projects
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Conducted By</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="fw-bold">{{ $project->title }}</td>
                        <td>{{ $project->conducted_by }}</td>
                        <td>
                            <span class="badge 
                                @if($project->status === 'Proposed') bg-info
                                @elseif($project->status === 'Ongoing') bg-warning
                                @else bg-success
                                @endif">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td>{{ $project->start_date->format('M d, Y') }}</td>
                        <td>{{ $project->end_date->format('M d, Y') }}</td>
                        <td>
                            <div class="gap-1 d-flex">
                                <a href="{{ route('ceso.projects.show', $project) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('ceso.projects.restore', $project) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore" onclick="return confirm('Restore this project?')">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            No archived projects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $projects->links() }}
</div>

@endsection
