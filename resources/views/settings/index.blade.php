@extends('layouts.app')

@section('title', 'Store & System Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Store & System Settings</h4>
        <p class="text-muted small mb-0">Configure store identity, branding logo, invoice numbers, tax rates, and pagination.</p>
    </div>
</div>

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header"><i class="bi bi-shop me-2 text-primary"></i> Store Identity & Branding</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Store Name <span class="text-danger">*</span></label>
                        <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $settings['store_name']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Store Address</label>
                        <textarea name="store_address" class="form-control" rows="2">{{ old('store_address', $settings['store_address']) }}</textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Store Phone</label>
                            <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone', $settings['store_phone']) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Store Email</label>
                            <input type="email" name="store_email" class="form-control" value="{{ old('store_email', $settings['store_email']) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Store Website URL</label>
                        <input type="url" name="store_website" class="form-control" value="{{ old('store_website', $settings['store_website']) }}">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Currency <span class="text-danger">*</span></label>
                            <input type="text" name="currency" class="form-control" value="{{ old('currency', $settings['currency']) }}" required placeholder="$, Rp, €">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Tax Rate (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="tax" class="form-control" value="{{ old('tax', $settings['tax']) }}" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Invoice Prefix <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header"><i class="bi bi-sliders me-2 text-warning"></i> System Parameters</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Date Display Format <span class="text-danger">*</span></label>
                        <select name="date_format" class="form-select" required>
                            <option value="Y-m-d" {{ $settings['date_format'] === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2026-08-14)</option>
                            <option value="d/m/Y" {{ $settings['date_format'] === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (14/08/2026)</option>
                            <option value="m/d/Y" {{ $settings['date_format'] === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (08/14/2026)</option>
                            <option value="d M Y" {{ $settings['date_format'] === 'd M Y' ? 'selected' : '' }}>14 Aug 2026</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">System Timezone <span class="text-danger">*</span></label>
                        <select name="timezone" class="form-select" required>
                            <option value="UTC" {{ $settings['timezone'] === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Jakarta" {{ $settings['timezone'] === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                            <option value="Asia/Singapore" {{ $settings['timezone'] === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                            <option value="America/New_York" {{ $settings['timezone'] === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ $settings['timezone'] === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Currency Format Display <span class="text-danger">*</span></label>
                        <select name="currency_format" class="form-select" required>
                            <option value="prefix" {{ $settings['currency_format'] === 'prefix' ? 'selected' : '' }}>Prefix ($ 100.00)</option>
                            <option value="suffix" {{ $settings['currency_format'] === 'suffix' ? 'selected' : '' }}>Suffix (100.00 $)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Default System Language <span class="text-danger">*</span></label>
                        <select name="default_language" class="form-select" required>
                            <option value="en" {{ $settings['default_language'] === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            <option value="id" {{ $settings['default_language'] === 'id' ? 'selected' : '' }}>🇮🇩 Bahasa Indonesia</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Items Per Page (Pagination) <span class="text-danger">*</span></label>
                        <input type="number" name="items_per_page" class="form-control" value="{{ old('items_per_page', $settings['items_per_page']) }}" min="5" max="100" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-3"><i class="bi bi-save me-1"></i> Save Store Settings</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
