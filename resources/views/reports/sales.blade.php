@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Sales & Revenue Report</h4>
        <p class="text-muted small mb-0">Detailed breakdown of transaction receipts, discounts, taxes, and net sales.</p>
    </div>
    <div class="d-flex gap-2 btn-print-hide">
        <a href="{{ route('reports.export', 'sales') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
        <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Report</button>
    </div>
</div>

<!-- Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Transactions</span>
            <h4 class="fw-bold m-0 text-dark">{{ $summary['total_transactions'] }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Discount</span>
            <h4 class="fw-bold m-0 text-danger">-{{ currency_format($summary['total_discount']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Tax</span>
            <h4 class="fw-bold m-0 text-warning">{{ currency_format($summary['total_tax']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Net Sales Revenue</span>
            <h4 class="fw-bold m-0 text-success">{{ currency_format($summary['net_sales']) }}</h4>
        </div>
    </div>
</div>

<div class="card card-custom mb-4 btn-print-hide">
    <div class="card-body">
        <form action="{{ route('reports.sales') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Customer</label>
                <select name="customer_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($customers as $c)<option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Cashier</label>
                <select name="cashier_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($cashiers as $usr)<option value="{{ $usr->id }}" {{ request('cashier_id') == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
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
                        <th>Invoice</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th>Payment</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th class="text-end">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $s)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $s->invoice_number }}</td>
                        <td class="small text-muted">{{ $s->sale_date->format('Y-m-d H:i') }}</td>
                        <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
                        <td class="small">{{ $s->user->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($s->payment_method) }}</span></td>
                        <td>{{ currency_format($s->subtotal) }}</td>
                        <td class="text-danger">-{{ currency_format($s->discount) }}</td>
                        <td>{{ currency_format($s->tax) }}</td>
                        <td class="text-end fw-bold text-primary">{{ currency_format($s->grand_total) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No sales matching filter criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
