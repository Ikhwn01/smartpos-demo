@extends('layouts.app')

@section('title', 'Purchase Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Supplier Purchase Report</h4>
        <p class="text-muted small mb-0">Total cost of inventory purchases and supplier orders.</p>
    </div>
    <button class="btn btn-primary btn-sm btn-print-hide" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Report</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Orders</span>
            <h4 class="fw-bold m-0 text-dark">{{ $summary['total_purchases'] }}</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Procurement Cost</span>
            <h4 class="fw-bold m-0 text-primary">{{ currency_format($summary['total_cost']) }}</h4>
        </div>
    </div>
</div>

<div class="card card-custom mb-4 btn-print-hide">
    <div class="card-body">
        <form action="{{ route('reports.purchases') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-4">
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
                        <th>Items Count</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $p)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $p->purchase_number }}</td>
                        <td class="fw-bold">{{ $p->supplier->name ?? '-' }}</td>
                        <td class="small text-muted">{{ date_format_custom($p->purchase_date) }}</td>
                        <td>{{ $p->items->count() }} items</td>
                        <td class="text-end fw-bold text-primary">{{ currency_format($p->total_amount) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No purchase records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
