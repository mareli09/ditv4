@extends('layouts.app')

@section('title', 'CESO | Verify Email')

@section('content')

<section style="background-color:#f4f6f8; min-height:calc(100vh - 72px); display:flex; align-items:center;" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white p-5 rounded shadow text-center">

                    <div class="mb-4">
                        <div style="width:72px;height:72px;background:#e8f0fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="fas fa-envelope-open-text" style="font-size:30px;color:#0a3d62;"></i>
                        </div>
                    </div>

                    <h3 class="mb-2">Verify Your Email Address</h3>
                    <p class="text-muted mb-4">
                        A verification link has been sent to <strong>{{ Auth::user()->email }}</strong>.<br>
                        Please check your inbox and click the link to activate your account.
                    </p>

                    @if(session('resent'))
                        <div class="alert alert-success">
                            {{ session('resent') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">
                            {{ session('info') }}
                        </div>
                    @endif

                    <p class="text-muted small mb-3">Didn't receive the email?</p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-redo me-2"></i> Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
