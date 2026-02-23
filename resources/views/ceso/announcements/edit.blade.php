@extends('layouts.ceso')

@section('title', 'Edit Announcement - CESO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Edit Announcement</h3>
        <a href="{{ route('ceso.announcements.index') }}" class="btn btn-outline-secondary">Back to Announcements</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('ceso.announcements.update', $announcement->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title *</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" 
                           placeholder="Announcement title" value="{{ old('title', $announcement->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Content *</label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" 
                              rows="8" placeholder="Write announcement details..." required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <strong>Created:</strong> {{ $announcement->created_at->format('M d, Y H:i') }} 
                    by <em>{{ $announcement->createdBy->name ?? 'System' }}</em>
                    @if($announcement->published_at)
                        | <strong>Published:</strong> {{ $announcement->published_at->format('M d, Y H:i') }}
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Announcement
                    </button>
                    <a href="{{ route('ceso.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
