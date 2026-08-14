@extends('layouts.app')

@section('title', 'Inventory Stock Audit Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Inventory Audit & Movement History</h4>
        <p class="text-muted small mb-0">Detailed log of every product stock increase, sale deduction, purchase, and manual adjustment.</p>
    </div>
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Inventory</a>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Change Qty</th>
                        <th>Old Stock</th>
                        <th>New Stock</th>
                        <th>Reference</th>
                        <th>Operator</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $tx)
                    <tr>
                        <td class="small text-muted">{{ $tx->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="fw-bold">{{ $tx->product->name ?? 'Deleted Product' }}</td>
                        <td>
                            <span class="badge {{ in_array($tx->type, ['in', 'purchase']) ? 'bg-success' : 'bg-danger' }}">
                                {{ strtoupper($tx->type) }}
                            </span>
                        </td>
                        <td class="fw-bold fs-6">{{ $tx->quantity }}</td>
                        <td>{{ $tx->old_stock }}</td>
                        <td class="fw-bold text-primary">{{ $tx->new_stock }}</td>
                        <td class="font-monospace small">{{ $tx->reference_number ?: '-' }}</td>
                        <td class="small">{{ $tx->user->name ?? 'System' }}</td>
                        <td class="small text-muted" style="max-width: 180px;">{{ $tx->notes ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No inventory transactions logged yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($history->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $history->links() }}
    </div>
    @endif
</div>
@endsection
