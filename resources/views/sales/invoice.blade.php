@extends('layouts.app')

@section('title', 'Invoice ' . $sale->invoice_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 btn-print-hide">
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Sales</a>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print Invoice</a>
    </div>
</div>

<div class="card card-custom p-4 shadow-sm" style="max-width: 800px; margin: 0 auto;">
    <!-- Store Header -->
    <div class="row pb-4 mb-4 border-bottom">
        <div class="col-6">
            <h3 class="fw-bold text-primary mb-1">{{ setting('store_name', 'SmartPOS Store') }}</h3>
            <p class="text-muted small mb-0">{{ setting('store_address', '123 Commercial Blvd') }}</p>
            <p class="text-muted small mb-0">Phone: {{ setting('store_phone', '+1 555-0192') }} | Email: {{ setting('store_email', 'info@smartpos.local') }}</p>
        </div>
        <div class="col-6 text-end">
            <h4 class="fw-bold text-uppercase text-secondary mb-1">INVOICE</h4>
            <div class="font-monospace fw-bold fs-5 text-dark mb-1">{{ $sale->invoice_number }}</div>
            <div class="small text-muted">Date: {{ $sale->sale_date->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <!-- Metadata -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="small text-muted text-uppercase fw-semibold">Billed To</div>
            <div class="fw-bold text-dark fs-6">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
            @if($sale->customer && $sale->customer->phone)
            <div class="small text-muted">{{ $sale->customer->phone }}</div>
            @endif
        </div>
        <div class="col-6 text-end">
            <div class="small text-muted text-uppercase fw-semibold">Cashier</div>
            <div class="fw-bold text-dark fs-6">{{ $sale->user->name ?? 'Staff Cashier' }}</div>
            <div class="small text-muted">Payment: {{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}</div>
        </div>
    </div>

    <!-- Item Table -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td class="fw-semibold">{{ $item->product_name }}</td>
                    <td class="text-end">{{ currency_format($item->unit_price) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">{{ currency_format($item->discount) }}</td>
                    <td class="text-end fw-bold">{{ currency_format($item->total_price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="row">
        <div class="col-6">
            <div class="p-3 bg-light rounded border">
                <div class="small fw-semibold text-muted mb-1">Payment Breakdown:</div>
                <div class="d-flex justify-content-between small">
                    <span>Paid Amount:</span>
                    <span class="fw-bold text-success">{{ currency_format($sale->paid_amount) }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span>Change Return:</span>
                    <span class="fw-bold text-danger">{{ currency_format($sale->change_amount) }}</span>
                </div>
            </div>
        </div>
        <div class="col-6">
            <table class="table table-sm table-borderless text-end">
                <tr>
                    <td class="text-muted">Subtotal:</td>
                    <td class="fw-bold">{{ currency_format($sale->subtotal) }}</td>
                </tr>
                @if($sale->discount > 0)
                <tr>
                    <td class="text-danger">Discount:</td>
                    <td class="fw-bold text-danger">-{{ currency_format($sale->discount) }}</td>
                </tr>
                @endif
                @if($sale->tax > 0)
                <tr>
                    <td class="text-muted">Tax:</td>
                    <td class="fw-bold">{{ currency_format($sale->tax) }}</td>
                </tr>
                @endif
                <tr class="border-top">
                    <td class="fw-bold fs-5">Grand Total:</td>
                    <td class="fw-bold fs-4 text-primary">{{ currency_format($sale->grand_total) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer Thank You -->
    <div class="text-center mt-5 pt-3 border-top text-muted small">
        <p class="mb-1">Thank you for your business!</p>
        <p class="mb-0">{{ setting('store_website', '') }}</p>
    </div>
</div>
@endsection
