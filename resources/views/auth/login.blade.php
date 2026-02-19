@extends('layouts.app')

@section('title', 'CESO | Login')

@section('content')

<section class="login-section py-5" style="background-color:#f4f6f8; min-height:calc(100vh - 72px); display:flex; align-items:center;">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h2 class="mb-3">Welcome Back to CESO</h2>
                    <p>
                        Access your dashboard to manage and participate
                        in Community Extension Services activities.
                    </p>
                </div>
            </div>

            <!-- RIGHT BOX -->
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow h-100">
                    <h3 class="mb-4 text-center">Login</h3>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Login
                        </button>
                    </form>

                    <div class="text-center">
                        <small>
                            Not yet part of the community?
                            <a href="{{ route('community.register') }}" class="text-primary fw-semibold">
                                Register here
                            </a>
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
