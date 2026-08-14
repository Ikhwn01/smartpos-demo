@extends('layouts.app')

@section('title', 'Sale Transaction Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Invoice: {{ $sale->invoice_number }}</h4>
        <p class="text-muted small mb-0">Date: {{ $sale->sale_date->format('Y-m-d H:i:s') }} | Cashier: {{ $sale->user->name ?? 'Cashier' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print Invoice</a>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">Customer & Payment Summary</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted">Customer Name</div>
                    <div class="fw-semibold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Payment Method</div>
                    <div class="fw-bold text-success">{{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Paid Amount</div>
                    <div class="fw-semibold">{{ currency_format($sale->paid_amount) }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Change Return</div>
                    <div class="fw-semibold text-danger">{{ currency_format($sale->change_amount) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-custom">
            <div class="card-header">Purchased Items Breakdown</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Discount</th>
                                <th class="text-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product_name }}</td>
                                <td>{{ currency_format($item->unit_price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ currency_format($item->discount) }}</td>
                                <td class="text-end fw-bold">{{ currency_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end fw-bold">{{ currency_format($sale->subtotal) }}</td>
                            </tr>
                            @if($sale->discount > 0)
                            <tr>
                                <td colspan="4" class="text-end text-danger fw-semibold">Discount:</td>
                                <td class="text-end text-danger fw-bold">-{{ currency_format($sale->discount) }}</td>
                            </tr>
                            @endif
                            @if($sale->tax > 0)
                            <tr>
                                <td colspan="4" class="text-end fw-semibold">Tax:</td>
                                <td class="text-end fw-bold">{{ currency_format($sale->tax) }}</td>
                            </tr>
                            @endif
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold fs-5">Grand Total:</td>
                                <td class="text-end fw-bold text-primary fs-4">{{ currency_format($sale->grand_total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
