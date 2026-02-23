@extends('layouts.ceso')

@section('title', 'Create Announcement - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Create Announcement</h3>
        <a href="{{ route('ceso.announcements.index') }}" class="btn btn-outline-secondary">Back to Announcements</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('ceso.announcements.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title *</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" 
                           placeholder="Announcement title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Content *</label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" 
                              rows="8" placeholder="Write announcement details..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Select Status</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publish Now</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Announcement
                    </button>
                    <a href="{{ route('ceso.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
