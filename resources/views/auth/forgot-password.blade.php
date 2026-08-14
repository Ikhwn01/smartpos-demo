@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="card card-custom p-4 shadow-sm">
    <h5 class="fw-bold mb-2">Reset Password</h5>
    <p class="text-muted small mb-4">Enter your account email address to receive password reset instructions.</p>

    @include('partials.alerts')

    <form action="{{ route('forgot-password') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">Send Reset Link</button>
    </form>

    <div class="text-center">
        <a href="{{ route('login') }}" class="small text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
    </div>
</div>
@endsection
