@extends('layouts.app')

@section('title', __('messages.inventory'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('messages.inventory_mgmt') }}</h4>
        <p class="text-muted small mb-0">{{ __('messages.inventory_catalog') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.history') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-clock-history me-1"></i> {{ __('messages.stock_audit_log') }}</a>
        <button class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#stockAdjustModal">
            <i class="bi bi-sliders me-1"></i> {{ __('messages.stock_adjustment') }}
        </button>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('inventory.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('messages.search') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_stock_statuses') }}</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>{{ __('messages.in_stock') }}</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>{{ __('messages.low_stock') }}</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('messages.out_of_stock') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter me-1"></i> {{ __('messages.filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">{{ __('messages.current_stock') }}</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.product_name') }}</th>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.current_stock') }}</th>
                                <th>{{ __('messages.min_limit') }}</th>
                                <th>{{ __('messages.stock_status') }}</th>
                                <th class="text-end">{{ __('messages.adjust') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $p->name }}</div>
                                    <small class="text-muted font-monospace">{{ $p->product_code }}</small>
                                </td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td class="fw-bold fs-6">{{ $p->stock }} {{ $p->unit }}</td>
                                <td>{{ $p->min_stock }} {{ $p->unit }}</td>
                                <td>
                                    @if($p->stock <= 0)
                                    <span class="badge bg-danger">{{ __('messages.out_of_stock') }}</span>
                                    @elseif($p->stock <= $p->min_stock)
                                    <span class="badge bg-warning text-dark">{{ __('messages.low_stock') }}</span>
                                    @else
                                    <span class="badge bg-success">{{ __('messages.in_stock') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light border" onclick="quickAdjust({{ $p->id }})" data-bs-toggle="modal" data-bs-target="#stockAdjustModal">
                                        <i class="bi bi-plus-slash-minus"></i> {{ __('messages.adjust') }}
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No inventory records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($products->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Recent History Sidebar -->
    <div class="col-lg-4 mb-4">
        <div class="card card-custom h-100">
            <div class="card-header">{{ __('messages.recent_adjustments') }}</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($transactions as $tx)
                    <div class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="small text-truncate" style="max-width: 160px;">{{ $tx->product->name ?? 'Deleted' }}</strong>
                            <span class="badge {{ in_array($tx->type, ['in', 'purchase']) ? 'bg-success' : 'bg-danger' }}">
                                {{ strtoupper($tx->type) }}: {{ $tx->type === 'out' ? '-' : '+' }}{{ $tx->quantity }}
                            </span>
                        </div>
                        <div class="small text-muted mb-1">{{ $tx->notes ?: 'Manual adjustment' }}</div>
                        <div class="d-flex justify-content-between small text-muted" style="font-size: 0.75rem;">
                            <span>By: {{ $tx->user->name ?? 'System' }}</span>
                            <span>{{ $tx->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted small">No recent stock movements.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.adjust') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ __('messages.stock_adjustment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('messages.products') }} <span class="text-danger">*</span></label>
                        <select name="product_id" id="modal-adjust-product-id" class="form-select" required>
                            <option value="">Choose product...</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">[{{ $p->product_code }}] {{ $p->name }} (Current: {{ $p->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="in">{{ __('messages.stock_in') }}</option>
                                <option value="out">{{ __('messages.stock_out') }}</option>
                                <option value="adjustment">{{ __('messages.set_stock') }}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Qty <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('messages.reason_notes') }} <span class="text-danger">*</span></label>
                        <textarea name="notes" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function quickAdjust(productId) {
    document.getElementById('modal-adjust-product-id').value = productId;
}
</script>
@endsection
