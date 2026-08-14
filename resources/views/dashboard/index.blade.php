@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('messages.overview_analytics') }}</h4>
        <p class="text-muted small mb-0">{{ __('messages.executive_dashboard') }}</p>
    </div>
    <a href="{{ route('pos.index') }}" class="btn btn-primary px-3 shadow-sm fw-semibold">
        <i class="bi bi-display me-1"></i> {{ __('messages.open_pos') }}
    </a>
</div>

<!-- Metrics Row 1 -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold">{{ __('messages.total_revenue') }}</div>
                <h4 class="fw-bold my-1 text-primary">{{ currency_format($metrics['total_sales']) }}</h4>
                <div class="small text-muted"><i class="bi bi-graph-up-arrow text-success"></i> {{ __('messages.lifetime_sales') }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold">{{ __('messages.todays_sales') }}</div>
                <h4 class="fw-bold my-1 text-success">{{ currency_format($metrics['today_sales']) }}</h4>
                <div class="small text-muted"><i class="bi bi-calendar-event me-1"></i> {{ __('messages.daily_revenue') }}</div>
            </div>
            <div class="stat-icon success">
                <i class="bi bi-cart-check"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold">{{ __('messages.todays_profit') }}</div>
                <h4 class="fw-bold my-1 text-info">{{ currency_format($metrics['today_profit']) }}</h4>
                <div class="small text-muted">Revenue - COGS - Expenses</div>
            </div>
            <div class="stat-icon info">
                <i class="bi bi-piggy-bank"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold">{{ __('messages.low_stock_warnings') }}</div>
                <h4 class="fw-bold my-1 text-danger">{{ $metrics['low_stock_products'] }}</h4>
                <div class="small text-muted"><i class="bi bi-exclamation-triangle me-1"></i> {{ __('messages.needs_reorder') }}</div>
            </div>
            <div class="stat-icon danger">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>
</div>

<!-- Metrics Row 2 -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">{{ __('messages.total_products') }}</span>
            <h5 class="fw-bold m-0 text-dark">{{ $metrics['total_products'] }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">{{ __('messages.total_customers') }}</span>
            <h5 class="fw-bold m-0 text-dark">{{ $metrics['total_customers'] }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">{{ __('messages.total_purchases') }}</span>
            <h5 class="fw-bold m-0 text-dark">{{ currency_format($metrics['total_purchases']) }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center mb-0">
            <span class="text-muted small">{{ __('messages.todays_expenses') }}</span>
            <h5 class="fw-bold m-0 text-warning">{{ currency_format($metrics['today_expenses']) }}</h5>
        </div>
    </div>
</div>

<!-- Sales Chart Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                    <span>{{ __('messages.sales_chart') }}</span>
                </div>
                <div class="btn-group btn-group-sm" id="period-selector">
                    <button class="btn btn-outline-secondary {{ $period === 'today' ? 'active' : '' }}" onclick="loadChartPeriod('today')">{{ __('messages.today') }}</button>
                    <button class="btn btn-outline-secondary {{ $period === 'this_week' ? 'active' : '' }}" onclick="loadChartPeriod('this_week')">{{ __('messages.this_week') }}</button>
                    <button class="btn btn-outline-secondary {{ $period === 'this_month' ? 'active' : '' }}" onclick="loadChartPeriod('this_month')">{{ __('messages.this_month') }}</button>
                    <button class="btn btn-outline-secondary {{ $period === 'this_year' ? 'active' : '' }}" onclick="loadChartPeriod('this_year')">{{ __('messages.this_year') }}</button>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 320px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-7 mb-4">
        <div class="card card-custom h-100 mb-0">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2 text-primary"></i> {{ __('messages.recent_transactions') }}</span>
                <a href="{{ route('sales.index') }}" class="small text-primary text-decoration-none">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.invoice') }}</th>
                                <th>{{ __('messages.customer') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.total') }}</th>
                                <th>{{ __('messages.payment') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}" class="fw-semibold text-decoration-none">{{ $sale->invoice_number }}</a>
                                </td>
                                <td>{{ $sale->customer->name ?? __('messages.walk_in_customer') }}</td>
                                <td class="small text-muted">{{ date_format_custom($sale->sale_date) }}</td>
                                <td class="fw-bold">{{ currency_format($sale->grand_total) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No sales recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-lg-5 mb-4">
        <div class="card card-custom h-100 mb-0">
            <div class="card-header">
                <span><i class="bi bi-exclamation-octagon me-2 text-danger"></i> {{ __('messages.low_stock_alert') }}</span>
                <a href="{{ route('inventory.index') }}" class="small text-primary text-decoration-none">{{ __('messages.audit_stock') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.products') }}</th>
                                <th>{{ __('messages.current_stock') }}</th>
                                <th>{{ __('messages.min_limit') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $prd)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-truncate" style="max-width: 140px;">{{ $prd->name }}</div>
                                    <small class="text-muted">{{ $prd->product_code }}</small>
                                </td>
                                <td class="fw-bold text-danger">{{ $prd->stock }} {{ $prd->unit }}</td>
                                <td>{{ $prd->min_stock }}</td>
                                <td>
                                    @if($prd->stock <= 0)
                                        <span class="badge bg-danger">{{ __('messages.out_of_stock') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ __('messages.low_stock') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">All product stocks are sufficient!</td>
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

@push('scripts')
<script>
let salesChartInstance = null;

function renderChart(labels, data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    if (salesChartInstance) {
        salesChartInstance.destroy();
    }

    salesChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ __('messages.total_revenue') }} ({{ setting('currency', '$') }})',
                data: data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

function loadChartPeriod(period) {
    document.querySelectorAll('#period-selector button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    fetch(`{{ route('dashboard.chart') }}?period=${period}`)
        .then(res => res.json())
        .then(data => {
            renderChart(data.labels, data.data);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    renderChart({!! json_encode($chartData['labels']) !!}, {!! json_encode($chartData['data']) !!});
});
</script>
@endpush
