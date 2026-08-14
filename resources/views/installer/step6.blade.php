@extends('layouts.installer')

@section('content')
<div class="text-center py-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 70px; height: 70px;">
        <i class="bi bi-check-lg display-4"></i>
    </div>
    <h4 class="fw-bold mb-2">Installation Complete!</h4>
    <p class="text-muted small mb-4">SmartPOS Point of Sale & Inventory System has been successfully installed.</p>

    <div class="p-3 bg-light rounded border text-start mb-4">
        <div class="fw-bold small text-dark mb-2"><i class="bi bi-shield-check text-primary me-1"></i> Admin Login Credentials:</div>
        <div class="small"><strong>Login URL:</strong> <a href="{{ route('login') }}">{{ route('login') }}</a></div>
        <div class="small"><strong>Email:</strong> admin@example.com</div>
        <div class="small"><strong>Password:</strong> password</div>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Go to Login Page</a>
</div>
@endsection
