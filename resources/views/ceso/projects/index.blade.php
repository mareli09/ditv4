@extends('layouts.ceso')

@section('title', 'CESO Projects')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Projects</h3>
        <p class="text-muted mb-0">Manage and monitor CESO projects</p>
    </div>
    <div class="gap-2 d-flex">
        <a href="{{ route('ceso.projects.archived') }}" class="btn btn-outline-secondary">
            <i class="fas fa-archive me-1"></i> Archived Projects
        </a>
        <a href="{{ route('ceso.projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Project
        </a>
    </div>
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
                                <a href="{{ route('ceso.projects.edit', $project) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ceso.projects.archive', $project) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Archive" onclick="return confirm('Archive this project?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            No projects found.
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
