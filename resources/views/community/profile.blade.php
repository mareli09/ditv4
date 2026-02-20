@extends('layouts.community')

@section('title', 'Profile Settings - Community Portal')

@section('content')

<div class="content-header">
    <h3><i class="fas fa-user-cog me-2"></i> Profile Settings</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->first_name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->last_name }}" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" value="{{ auth()->user()->phone }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->address }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Barangay</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->barangay }}" disabled>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Contact IT support to update your profile information.
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="#">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password (min 6 characters)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
