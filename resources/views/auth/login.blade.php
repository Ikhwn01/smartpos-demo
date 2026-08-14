@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="card card-custom p-4 shadow-sm">
    <h5 class="fw-bold mb-3">Sign In to Your Account</h5>

    @include('partials.alerts')

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', 'admin@example.com') }}" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label small fw-semibold mb-0">Password</label>
                <a href="{{ route('forgot-password') }}" class="small text-primary text-decoration-none">Forgot password?</a>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" value="password" required>
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label small text-muted" for="remember">Remember me on this device</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">Sign In</button>
    </form>

    <div class="p-3 bg-light rounded border text-start">
        <div class="fw-bold small mb-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Live Demo Access:</div>
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ route('demo.login', 'admin') }}" class="btn btn-sm btn-primary w-100 fw-semibold">
                    <i class="bi bi-person-shield me-1"></i> Admin Demo
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('demo.login', 'staff') }}" class="btn btn-sm btn-outline-secondary w-100 fw-semibold">
                    <i class="bi bi-display me-1"></i> Cashier Demo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
