@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
        <p class="text-muted small mb-0">Customer Code: <span class="font-monospace">{{ $customer->customer_code }}</span></p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Customers</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">Customer Profile</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted">Phone Number</div>
                    <div class="fw-semibold">{{ $customer->phone ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Email Address</div>
                    <div class="fw-semibold">{{ $customer->email ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Address</div>
                    <div class="fw-semibold">{{ $customer->address ?: '-' }}</div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Total Sales Transactions</span>
                    <span class="badge bg-primary fs-6">{{ $customer->sales->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">Purchase & Sales History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Grand Total</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->sales as $sale)
                            <tr>
                                <td class="fw-semibold font-monospace">{{ $sale->invoice_number }}</td>
                                <td class="small text-muted">{{ date_format_custom($sale->sale_date) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $sale->items->count() }} item(s)</span></td>
                                <td class="fw-bold text-primary">{{ currency_format($sale->grand_total) }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ strtoupper($sale->payment_method) }}</span></td>
                                <td><a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i> View</a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No past transactions found for this customer.</td>
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
