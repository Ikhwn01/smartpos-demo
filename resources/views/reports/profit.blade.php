@extends('layouts.app')

@section('title', __('messages.profit_loss'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('messages.profit_loss') }}</h4>
        <p class="text-muted small mb-0">{{ __('messages.net_profit') }} = {{ __('messages.total_revenue') }} - {{ __('messages.cogs') }} - {{ __('messages.operating_expenses') }}</p>
    </div>
    <button class="btn btn-primary btn-sm btn-print-hide" onclick="window.print()"><i class="bi bi-printer me-1"></i> {{ __('messages.print') }}</button>
</div>

<div class="card card-custom mb-4 btn-print-hide">
    <div class="card-body">
        <form action="{{ route('reports.profit') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-5">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter me-1"></i> {{ __('messages.filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card card-custom shadow-sm p-4">
            <h5 class="fw-bold border-bottom pb-3 mb-4 text-center">{{ strtoupper(__('messages.profit_loss')) }}</h5>

            <table class="table table-borderless fs-6">
                <tbody>
                    <tr>
                        <td class="fw-semibold"><i class="bi bi-graph-up text-success me-2"></i> {{ __('messages.total_revenue') }}</td>
                        <td class="text-end fw-bold text-success fs-5">{{ currency_format($profitData['revenue']) }}</td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-muted small">Less: {{ __('messages.cogs') }}</td>
                        <td class="text-end text-danger fw-semibold">-{{ currency_format($profitData['cogs']) }}</td>
                    </tr>
                    <tr class="table-light border-top border-bottom">
                        <td class="fw-bold fs-5">Gross Operating Profit</td>
                        <td class="text-end fw-bold fs-5 text-primary">{{ currency_format($profitData['gross_profit']) }}</td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-muted small">Less: {{ __('messages.operating_expenses') }}</td>
                        <td class="text-end text-danger fw-semibold">-{{ currency_format($profitData['expenses']) }}</td>
                    </tr>
                    <tr class="table-primary border-top" style="border-top-width: 2px !important;">
                        <td class="fw-bold fs-4">{{ __('messages.net_profit') }}</td>
                        <td class="text-end fw-bold fs-3 {{ $profitData['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ currency_format($profitData['net_profit']) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
