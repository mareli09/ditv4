@extends('layouts.it')

@section('title', 'Edit User')

@section('content')

<h3 class="fw-bold mb-4">Edit User Information</h3>

<div class="card shadow-sm">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('it.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- User Information Section -->
            <h5 class="fw-bold mt-0 mb-3 border-bottom pb-2">User Information</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Account Credentials Section -->
            <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Account Credentials</h5>
            <p class="text-muted small mb-3">Leave password fields empty to keep the current password</p>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter new password (min 6 characters)">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Re-enter new password">
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Role & Status Section -->
            <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Role & Status</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Select Role --</option>
                        <option value="IT" {{ old('role', $user->role) === 'IT' ? 'selected' : '' }}>IT Staff</option>
                        <option value="CESO" {{ old('role', $user->role) === 'CESO' ? 'selected' : '' }}>CESO Staff</option>
                        <option value="Faculty" {{ old('role', $user->role) === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                        <option value="Student" {{ old('role', $user->role) === 'Student' ? 'selected' : '' }}>Student</option>
                        <option value="Community" {{ old('role', $user->role) === 'Community' ? 'selected' : '' }}>Community</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Account Status</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $user->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            <i class="fas fa-check-circle text-success me-1"></i> Active
                        </label>
                    </div>
                </div>
            </div>

            <!-- System Information Section -->
            <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">System Information</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info mb-0">
                        <strong>User ID:</strong> #{{ $user->id }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info mb-0">
                        <strong>Created:</strong> {{ $user->created_at->format('M d, Y @ H:i A') }}
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                <a href="{{ route('it.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Update User
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
