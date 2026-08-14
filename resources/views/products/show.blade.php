@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $product->name }}</h4>
        <p class="text-muted small mb-0">Code: <span class="font-monospace fw-semibold">{{ $product->product_code }}</span> | Barcode: <span class="font-monospace fw-semibold">{{ $product->barcode ?? 'N/A' }}</span></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-custom text-center p-3 h-100">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded border mb-3 mx-auto" style="max-height: 220px; object-fit: cover;">
            <h5 class="fw-bold text-primary">{{ currency_format($product->selling_price) }}</h5>
            <div class="small text-muted mb-2">Cost Price: {{ currency_format($product->purchase_price) }}</div>
            <div>
                <span class="badge {{ $product->stock <= $product->min_stock ? 'bg-danger' : 'bg-success' }} fs-6 px-3 py-2">
                    Current Stock: {{ $product->stock }} {{ $product->unit }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-custom mb-4">
            <div class="card-header">Product Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="small text-muted">Category</div>
                        <div class="fw-semibold">{{ $product->category->name ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Supplier</div>
                        <div class="fw-semibold">{{ $product->supplier->name ?? 'None' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Min Warning Level</div>
                        <div class="fw-semibold">{{ $product->min_stock }} {{ $product->unit }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Status</div>
                        <div>
                            @if($product->status)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Description</div>
                        <p class="m-0 text-muted">{{ $product->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header">Stock Movement Audit History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Old Stock</th>
                                <th>New Stock</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->inventoryTransactions as $tx)
                            <tr>
                                <td class="small text-muted">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge {{ in_array($tx->type, ['in', 'purchase']) ? 'bg-success' : 'bg-danger' }}">
                                        {{ strtoupper($tx->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $tx->quantity }}</td>
                                <td>{{ $tx->old_stock }}</td>
                                <td>{{ $tx->new_stock }}</td>
                                <td class="small">{{ $tx->user->name ?? 'System' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">No stock movement recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
