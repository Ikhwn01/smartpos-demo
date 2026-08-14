@extends('layouts.app')

@section('title', 'Supplier Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Suppliers & Distributors</h4>
        <p class="text-muted small mb-0">Manage vendor contact details and wholesale product suppliers.</p>
    </div>
    <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="bi bi-plus-lg me-1"></i> Add Supplier
    </button>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('suppliers.index') }}" method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code, name, company, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Supplier Code</th>
                        <th>Supplier / Company</th>
                        <th>Contact Person</th>
                        <th>Phone & Email</th>
                        <th>Address</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $sup)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $sup->supplier_code }}</td>
                        <td>
                            <div class="fw-bold">{{ $sup->name }}</div>
                            @if($sup->company)<small class="text-muted">{{ $sup->company }}</small>@endif
                        </td>
                        <td>{{ $sup->contact_person ?: '-' }}</td>
                        <td>
                            <div class="small"><i class="bi bi-telephone me-1 text-muted"></i>{{ $sup->phone ?: '-' }}</div>
                            <div class="small"><i class="bi bi-envelope me-1 text-muted"></i>{{ $sup->email ?: '-' }}</div>
                        </td>
                        <td class="small text-muted" style="max-width: 200px;">{{ $sup->address ?: '-' }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $sup->id }}"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('suppliers.destroy', $sup->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light border text-danger btn-delete-confirm" data-name="{{ $sup->name }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editSupplierModal{{ $sup->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('suppliers.update', $sup->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Supplier Code</label>
                                            <input type="text" name="supplier_code" class="form-control" value="{{ $sup->supplier_code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Supplier Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $sup->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Company Name</label>
                                            <input type="text" name="company" class="form-control" value="{{ $sup->company }}">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $sup->phone }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $sup->email }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Contact Person</label>
                                            <input type="text" name="contact_person" class="form-control" value="{{ $sup->contact_person }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Address</label>
                                            <textarea name="address" class="form-control" rows="2">{{ $sup->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Supplier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No suppliers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Supplier Code <span class="text-danger">*</span></label>
                        <input type="text" name="supplier_code" class="form-control" value="SUP-{{ str_pad(App\Models\Supplier::count() + 1, 4, '0', STR_PAD_LEFT) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Global Trade Ltd" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Company Name</label>
                        <input type="text" name="company" class="form-control">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
