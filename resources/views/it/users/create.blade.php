@extends('layouts.it')

@section('title', 'Create User')

@section('content')

<h3 class="fw-bold mb-4">Create New User</h3>

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

        <form action="{{ route('it.users.store') }}" method="POST">
            @csrf

            <!-- Basic Information Section -->
            <h5 class="fw-bold mt-0 mb-3 border-bottom pb-2">Basic Information</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" placeholder="Enter full name" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" placeholder="Enter email address" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Account Credentials Section -->
            <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Account Credentials</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter password (min 6 characters)" required>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Re-enter password" required>
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
                        <option value="IT" {{ old('role') === 'IT' ? 'selected' : '' }}>IT Staff</option>
                        <option value="CESO" {{ old('role') === 'CESO' ? 'selected' : '' }}>CESO Staff</option>
                        <option value="Faculty" {{ old('role') === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                        <option value="Student" {{ old('role') === 'Student' ? 'selected' : '' }}>Student</option>
                        <option value="Community" {{ old('role') === 'Community' ? 'selected' : '' }}>Community</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Account Status</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                        <label class="form-check-label" for="isActive">
                            <i class="fas fa-check-circle text-success me-1"></i> Active
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                <a href="{{ route('it.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Create User
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
