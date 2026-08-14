@extends('layouts.app')

@section('title', __('messages.pos_terminal'))

@section('content')
<div class="pos-container card card-custom mb-0 border-0">
    <div class="row g-0 h-100 w-100">
        <!-- Left Side: Products Catalog -->
        <div class="col-lg-8 d-flex flex-column border-end" style="height: 100%;">
            <!-- Top Filter Bar -->
            <div class="p-3 border-bottom bg-light">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="pos-search-input" class="form-control border-start-0" placeholder="{{ __('messages.search_pos_ph') }}" autofocus>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex gap-1 overflow-x-auto pb-1 pb-md-0" id="category-tabs-container">
                        <button class="btn btn-sm btn-primary pos-cat-btn active" data-cat-id="all">{{ __('messages.all_items') }}</button>
                        @foreach($categories as $cat)
                        <button class="btn btn-sm btn-outline-secondary pos-cat-btn text-nowrap" data-cat-id="{{ $cat->id }}">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="pos-products-panel p-3">
                <div class="row g-3" id="pos-product-grid">
                    <!-- Products dynamically rendered by pos.js -->
                </div>
            </div>
        </div>

        <!-- Right Side: Live Cart & Summary -->
        <div class="col-lg-4 d-flex flex-column bg-white" style="height: 100%;">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h6 class="fw-bold m-0"><i class="bi bi-cart3 me-2 text-primary"></i> {{ __('messages.shopping_cart') }}</h6>
                <button class="btn btn-sm btn-outline-danger" id="pos-clear-cart-btn"><i class="bi bi-trash me-1"></i> {{ __('messages.clear') }}</button>
            </div>

            <!-- Cart Items Container -->
            <div class="flex-grow-1 overflow-y-auto" style="min-height: 250px;">
                <div id="pos-cart-empty" class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x display-4"></i>
                    <p class="mt-2 mb-0">{{ __('messages.cart_empty') }}</p>
                </div>
                <div id="pos-cart-items" style="display: none;"></div>
            </div>

            <!-- Summary Footer -->
            <div class="p-3 border-top bg-light">
                <div class="d-flex justify-content-between mb-1 small text-muted">
                    <span>{{ __('messages.subtotal') }}:</span>
                    <span id="pos-subtotal" class="fw-semibold">$ 0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1 small text-muted">
                    <span>{{ __('messages.discount') }}:</span>
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <span class="input-group-text">{{ setting('currency', '$') }}</span>
                        <input type="number" step="0.01" id="pos-discount-input" class="form-control text-end" value="0.00">
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>{{ __('messages.tax') }} ({{ setting('tax', 0) }}%):</span>
                    <span id="pos-tax" class="fw-semibold">$ 0.00</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold fs-5">{{ __('messages.grand_total') }}:</span>
                    <span id="pos-grand-total" class="fw-bold fs-4 text-primary">$ 0.00</span>
                </div>

                <button id="pos-checkout-btn" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#posCheckoutModal" disabled>
                    <i class="bi bi-credit-card me-2"></i> {{ __('messages.pay_now') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="posCheckoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-cash-stack me-2"></i> {{ __('messages.process_checkout') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4 p-3 bg-light rounded border">
                    <div class="text-muted small text-uppercase">{{ __('messages.amount_payable') }}</div>
                    <div class="fw-bold fs-2 text-primary" id="modal-grand-total">$ 0.00</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">{{ __('messages.customer') }}</label>
                    <select id="modal-customer-id" class="form-select">
                        <option value="">{{ __('messages.walk_in_customer') }}</option>
                        @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">{{ __('messages.payment_method') }} <span class="text-danger">*</span></label>
                    <select id="modal-payment-method" class="form-select">
                        <option value="cash">{{ __('messages.cash') }}</option>
                        <option value="bank_transfer">{{ __('messages.bank_transfer') }}</option>
                        <option value="e_wallet">{{ __('messages.e_wallet') }}</option>
                        <option value="debit_card">{{ __('messages.debit_card') }}</option>
                        <option value="credit_card">{{ __('messages.credit_card') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">{{ __('messages.paid_amount') }} <span class="text-danger">*</span></label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text fw-bold">{{ setting('currency', '$') }}</span>
                        <input type="number" step="0.01" id="modal-paid-amount" class="form-control fw-bold fs-4 text-success" placeholder="0.00" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded border">
                    <span class="fw-semibold">{{ __('messages.change_return') }}:</span>
                    <span id="modal-change-amount" class="fw-bold fs-4 text-danger">$ 0.00</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" id="modal-confirm-pay-btn" class="btn btn-success fw-bold px-4 py-2">
                    <i class="bi bi-check-circle me-1"></i> {{ __('messages.confirm_payment') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/pos.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.pos = new PosSystem({
        searchUrl: "{{ route('pos.search') }}",
        checkoutUrl: "{{ route('pos.checkout') }}",
        csrfToken: "{{ csrf_token() }}",
        taxPercent: "{{ $taxPercent }}",
        currency: "{{ $currencySymbol }}"
    });
});
</script>
@endpush
