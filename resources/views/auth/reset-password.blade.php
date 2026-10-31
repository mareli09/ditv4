@extends('layouts.app')

@section('title', 'CESO | Reset Password')

@section('content')

<section style="background-color:#f4f6f8; min-height:calc(100vh - 72px); display:flex; align-items:center;" class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h2 class="mb-3">Set a New Password</h2>
                    <p>
                        Create a strong password for your CESO account.
                        Your new password must be at least 8 characters long.
                    </p>
                    <ul class="text-muted small mt-3 ps-3">
                        <li>Use a mix of letters, numbers, and symbols</li>
                        <li>Avoid using your name or email</li>
                        <li>Do not reuse old passwords</li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h3 class="mb-4 text-center">New Password</h3>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $email ?? '') }}"
                                   required readonly>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required autofocus>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="passwordConfirmInput"
                                       class="form-control"
                                       required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-key me-2"></i> Reset Password
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    function toggleVisibility(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        btn.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            btn.querySelector('i').classList.toggle('fa-eye');
            btn.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    toggleVisibility('passwordInput', 'togglePassword');
    toggleVisibility('passwordConfirmInput', 'togglePasswordConfirm');
});
</script>

@endsection
