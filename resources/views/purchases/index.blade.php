@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Supplier Purchase Orders</h4>
        <p class="text-muted small mb-0">Record incoming inventory shipments and supplier purchase orders.</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-plus-lg me-1"></i> New Purchase Order
    </a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('purchases.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search PO number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="supplier_id" class="form-select form-select-sm">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter me-1"></i> Filter</button>
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
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Total Items</th>
                        <th>Total Cost</th>
                        <th>Payment Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $po)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $po->purchase_number }}</td>
                        <td class="fw-bold">{{ $po->supplier->name ?? 'Deleted Supplier' }}</td>
                        <td class="small text-muted">{{ date_format_custom($po->purchase_date) }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $po->items->count() }} item(s)</span></td>
                        <td class="fw-bold text-primary">{{ currency_format($po->total_amount) }}</td>
                        <td>
                            <span class="badge bg-success">Received & Stock Added</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('purchases.show', $po->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i> Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No purchase orders created yet. Click 'New Purchase Order' to record incoming stock.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchases->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection
