@extends('layouts.app')

@section('title', 'Customer Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Customers Management</h4>
        <p class="text-muted small mb-0">Manage store clients, phone records, and purchase history.</p>
    </div>
    <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        <i class="bi bi-plus-lg me-1"></i> Add Customer
    </button>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('customers.index') }}" method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search customer code, name, phone..." value="{{ request('search') }}">
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
                        <th>Code</th>
                        <th>Customer Name</th>
                        <th>Phone & Email</th>
                        <th>Address</th>
                        <th>Total Orders</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $cust)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $cust->customer_code }}</td>
                        <td class="fw-bold">
                            <a href="{{ route('customers.show', $cust->id) }}" class="text-decoration-none text-dark">{{ $cust->name }}</a>
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-telephone me-1 text-muted"></i>{{ $cust->phone ?: '-' }}</div>
                            <div class="small"><i class="bi bi-envelope me-1 text-muted"></i>{{ $cust->email ?: '-' }}</div>
                        </td>
                        <td class="small text-muted">{{ $cust->address ?: '-' }}</td>
                        <td><span class="badge bg-primary">{{ $cust->sales_count }} sales</span></td>
                        <td class="text-end">
                            <a href="{{ route('customers.show', $cust->id) }}" class="btn btn-sm btn-light border" title="Transaction History"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editCustomerModal{{ $cust->id }}"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('customers.destroy', $cust->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light border text-danger btn-delete-confirm" data-name="{{ $cust->name }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editCustomerModal{{ $cust->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('customers.update', $cust->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Customer Code</label>
                                            <input type="text" name="customer_code" class="form-control" value="{{ $cust->customer_code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $cust->name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $cust->phone }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $cust->email }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Address</label>
                                            <textarea name="address" class="form-control" rows="2">{{ $cust->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Customer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $customers->links() }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Customer Code <span class="text-danger">*</span></label>
                        <input type="text" name="customer_code" class="form-control" value="CUST-{{ str_pad(App\Models\Customer::count() + 1, 4, '0', STR_PAD_LEFT) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
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
                        <label class="form-label small fw-semibold">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
