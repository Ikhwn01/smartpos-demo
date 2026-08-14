@extends('layouts.app')

@section('title', 'Best Selling Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Top Best Selling Products</h4>
        <p class="text-muted small mb-0">Ranking of products by total sales volume and generated revenue.</p>
    </div>
    <button class="btn btn-primary btn-sm btn-print-hide" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Report</button>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Rank</th>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th class="text-center">Total Quantity Sold</th>
                        <th class="text-end">Total Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestSellers as $index => $item)
                    <tr>
                        <td>
                            <span class="badge rounded-circle {{ $index < 3 ? 'bg-warning text-dark fs-6' : 'bg-light text-dark border' }}" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->product->name ?? 'Deleted Product' }}</div>
                            <small class="text-muted font-monospace">{{ $item->product->product_code ?? '' }}</small>
                        </td>
                        <td>{{ $item->product->category->name ?? '-' }}</td>
                        <td class="text-center fw-bold fs-6 text-primary">{{ $item->total_qty }} {{ $item->product->unit ?? 'pcs' }}</td>
                        <td class="text-end fw-bold text-success">{{ currency_format($item->total_revenue) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No sales data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
