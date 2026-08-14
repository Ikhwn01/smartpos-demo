@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Purchase Order: {{ $purchase->purchase_number }}</h4>
        <p class="text-muted small mb-0">Received Date: {{ date_format_custom($purchase->purchase_date) }}</p>
    </div>
    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to PO List</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">Supplier Info</div>
            <div class="card-body">
                <div class="fw-bold fs-5 text-primary mb-1">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                <div class="small text-muted mb-3">{{ $purchase->supplier->company }}</div>
                <div class="small text-muted mb-1"><i class="bi bi-telephone me-1"></i>{{ $purchase->supplier->phone ?: '-' }}</div>
                <div class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i>{{ $purchase->supplier->email ?: '-' }}</div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Created By</span>
                    <span class="fw-semibold small">{{ $purchase->user->name ?? 'System' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-custom">
            <div class="card-header">Purchased Items & Cost Breakdown</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Cost Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product->name ?? 'Deleted Product' }}</td>
                                <td>{{ $item->quantity }} {{ $item->product->unit ?? 'pcs' }}</td>
                                <td>{{ currency_format($item->purchase_price) }}</td>
                                <td class="text-end fw-bold">{{ currency_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end fw-bold">{{ currency_format($purchase->subtotal) }}</td>
                            </tr>
                            @if($purchase->discount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-danger fw-semibold">Discount:</td>
                                <td class="text-end text-danger fw-bold">-{{ currency_format($purchase->discount) }}</td>
                            </tr>
                            @endif
                            @if($purchase->tax > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Tax:</td>
                                <td class="text-end fw-bold">{{ currency_format($purchase->tax) }}</td>
                            </tr>
                            @endif
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold fs-6">Grand Total Cost:</td>
                                <td class="text-end fw-bold text-primary fs-5">{{ currency_format($purchase->total_amount) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
