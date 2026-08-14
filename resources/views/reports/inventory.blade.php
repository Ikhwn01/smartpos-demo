@extends('layouts.app')

@section('title', 'Inventory Valuation Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Inventory Valuation Report</h4>
        <p class="text-muted small mb-0">Total stock inventory quantity and estimated asset market value.</p>
    </div>
    <div class="d-flex gap-2 btn-print-hide">
        <a href="{{ route('reports.export', 'inventory') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
        <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Report</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Catalog Items</span>
            <h4 class="fw-bold m-0 text-dark">{{ $summary['total_items'] }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Total Stock Units</span>
            <h4 class="fw-bold m-0 text-info">{{ $summary['total_stock'] }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Asset Cost Value</span>
            <h4 class="fw-bold m-0 text-warning">{{ currency_format($summary['total_cost_value']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">Retail Market Value</span>
            <h4 class="fw-bold m-0 text-success">{{ currency_format($summary['total_retail_value']) }}</h4>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Purchase Cost</th>
                        <th>Retail Price</th>
                        <th class="text-end">Total Cost Value</th>
                        <th class="text-end">Total Retail Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $p->product_code }}</td>
                        <td class="fw-bold">{{ $p->name }}</td>
                        <td>{{ $p->category->name ?? '-' }}</td>
                        <td class="fw-bold">{{ $p->stock }} {{ $p->unit }}</td>
                        <td>{{ currency_format($p->purchase_price) }}</td>
                        <td>{{ currency_format($p->selling_price) }}</td>
                        <td class="text-end fw-semibold text-muted">{{ currency_format($p->stock * $p->purchase_price) }}</td>
                        <td class="text-end fw-bold text-success">{{ currency_format($p->stock * $p->selling_price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
