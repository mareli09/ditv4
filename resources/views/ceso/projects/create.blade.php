@extends('layouts.ceso')

@section('title', 'Create Project')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Create Project</h3>
    <p class="text-muted">Create a new CESO project</p>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('ceso.projects.store') }}" method="POST">
            @csrf

            <!-- PROJECT TITLE -->
            <div class="mb-3">
                <label class="form-label fw-bold">Project Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- PROJECT DESCRIPTION -->
            <div class="mb-3">
                <label class="form-label fw-bold">Project Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- CONDUCTED BY -->
            <div class="mb-3">
                <label class="form-label fw-bold">Conducted By</label>
                <input type="text" name="conducted_by" class="form-control @error('conducted_by') is-invalid @enderror" placeholder="e.g., Research Office, IT Department" value="{{ old('conducted_by') }}" required>
                @error('conducted_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- TARGET AUDIENCE -->
            <div class="mb-3">
                <label class="form-label fw-bold">Target Audience</label>
                <input type="text" name="target_audience" class="form-control @error('target_audience') is-invalid @enderror" placeholder="e.g., Faculty, Students, Community" value="{{ old('target_audience') }}" required>
                @error('target_audience')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- DATES -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- STATUS -->
            <div class="mb-3">
                <label class="form-label fw-bold">Project Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="Proposed" {{ old('status') === 'Proposed' ? 'selected' : '' }}>Proposed</option>
                    <option value="Ongoing" {{ old('status') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- REMARKS -->
            <div class="mb-4">
                <label class="form-label fw-bold">Remarks</label>
                <textarea name="remarks" class="form-control" rows="3" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Project
                </button>
                <a href="{{ route('ceso.projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
