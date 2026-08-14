@extends('layouts.app')

@section('title', 'Sales History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Sales & Invoice History</h4>
        <p class="text-muted small mb-0">Track completed point of sale orders, invoice receipts, and cash flows.</p>
    </div>
    <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm fw-semibold"><i class="bi bi-display me-1"></i> New POS Transaction</a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('sales.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Invoice number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Date From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Date To">
            </div>
            <div class="col-md-2">
                <select name="customer_id" class="form-select form-select-sm">
                    <option value="">All Customers</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">All Payment</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="e_wallet" {{ request('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="debit_card" {{ request('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter"></i></button>
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
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Items</th>
                        <th>Grand Total</th>
                        <th>Payment Method</th>
                        <th>Cashier</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="fw-semibold font-monospace">
                            <a href="{{ route('sales.show', $sale->id) }}" class="text-decoration-none">{{ $sale->invoice_number }}</a>
                        </td>
                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                        <td class="small text-muted">{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $sale->items->count() }} items</span></td>
                        <td class="fw-bold text-primary">{{ currency_format($sale->grand_total) }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}</span></td>
                        <td class="small">{{ $sale->user->name ?? 'Cashier' }}</td>
                        <td class="text-end">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-light border" title="Details"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-light border" title="Invoice View"><i class="bi bi-receipt"></i></a>
                            <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-sm btn-light border text-primary" title="Print"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No sales transactions recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection
