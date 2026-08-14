@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">User Account Profile</h4>
        <p class="text-muted small mb-0">Manage your personal info and security settings.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">
                <i class="bi bi-person-circle me-2 text-primary"></i> Personal Details
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff' }}" class="rounded-circle shadow-sm mb-3" width="100" height="100" style="object-fit: cover;">
                        <div>
                            <label class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-camera me-1"></i> Upload Avatar
                                <input type="file" name="avatar" class="d-none" onchange="this.form.submit()">
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role</label>
                        <input type="text" class="form-control bg-light" value="{{ strtoupper($user->role) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Profile Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">
                <i class="bi bi-shield-lock me-2 text-warning"></i> Change Password
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning text-white fw-semibold">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
