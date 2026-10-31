@extends('layouts.app')

@section('title', 'CESO | Forgot Password')

@section('content')

<section style="background-color:#f4f6f8; min-height:calc(100vh - 72px); display:flex; align-items:center;" class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h2 class="mb-3">Forgot Your Password?</h2>
                    <p>
                        No worries! Enter your registered email address and we'll
                        send you a link to reset your password.
                    </p>
                    <p class="text-muted small mt-3">
                        <i class="fas fa-lock-open me-1"></i>
                        The reset link will expire after 60 minutes.
                    </p>
                </div>
            </div>

            <!-- RIGHT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h3 class="mb-4 text-center">Reset Password</h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="Enter your registered email"
                                   required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-muted small">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
