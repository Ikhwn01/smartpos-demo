@extends('layouts.installer')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-4-circle-fill text-primary me-2"></i> Step 4: Create Admin Account</h5>
<p class="text-muted small mb-4">Set up master Super Administrator credentials to manage your store system.</p>

<form action="{{ route('install.step4.save') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Admin Full Name <span class="text-danger">*</span></label>
        <input type="text" name="admin_name" class="form-control" value="System Administrator" required>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-semibold">Admin Email <span class="text-danger">*</span></label>
        <input type="email" name="admin_email" class="form-control" value="admin@example.com" required>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="admin_password" class="form-control" value="password" required minlength="6">
        </div>
        <div class="col-6">
            <label class="form-label small fw-semibold">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="admin_password_confirmation" class="form-control" value="password" required minlength="6">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <span class="small text-muted">Step 4 of 6</span>
        <button type="submit" class="btn btn-primary fw-semibold">Save Admin Account <i class="bi bi-arrow-right ms-1"></i></button>
    </div>
</form>
@endsection
