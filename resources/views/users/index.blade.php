@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">User & Role Management</h4>
        <p class="text-muted small mb-0">Create staff accounts, assign Administrator/Staff roles, and toggle access status.</p>
    </div>
    <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i> Add New User
    </button>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>System Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $usr)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $usr->avatar ? asset('storage/' . $usr->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($usr->name) . '&background=4f46e5&color=fff' }}" class="rounded-circle me-2" width="36" height="36">
                                <div class="fw-bold">{{ $usr->name }}</div>
                            </div>
                        </td>
                        <td>{{ $usr->email }}</td>
                        <td>{{ $usr->phone ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $usr->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                                {{ strtoupper($usr->role) }}
                            </span>
                        </td>
                        <td>
                            @if($usr->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $usr->id }}"><i class="bi bi-pencil"></i></button>
                            @if($usr->id !== auth()->id())
                            <form action="{{ route('users.destroy', $usr->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light border text-danger btn-delete-confirm" data-name="{{ $usr->name }}"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal{{ $usr->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('users.update', $usr->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $usr->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Email Address</label>
                                            <input type="email" name="email" class="form-control" value="{{ $usr->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" value="{{ $usr->phone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
                                            <select name="role" class="form-select" required>
                                                <option value="admin" {{ $usr->role === 'admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
                                                <option value="staff" {{ $usr->role === 'staff' ? 'selected' : '' }}>Staff (Cashier / POS Only)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">New Password (Leave blank to keep current)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Account Status</label>
                                            <select name="status" class="form-select">
                                                <option value="active" {{ $usr->status === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ $usr->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Sarah Jenkins" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="sarah@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="staff">Staff (Cashier / Sales)</option>
                            <option value="admin">Administrator (Full System Access)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Account Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
