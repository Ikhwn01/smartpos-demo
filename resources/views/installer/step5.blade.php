@extends('layouts.installer')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-5-circle-fill text-primary me-2"></i> Step 5: Store Configuration</h5>
<p class="text-muted small mb-4">Set default store identity and sales tax parameters.</p>

<form action="{{ route('install.step5.save') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Store Name <span class="text-danger">*</span></label>
        <input type="text" name="store_name" class="form-control" value="SmartPOS Retail Megastore" required>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <label class="form-label small fw-semibold">Currency Symbol <span class="text-danger">*</span></label>
            <input type="text" name="currency" class="form-control" value="$" required>
        </div>
        <div class="col-4">
            <label class="form-label small fw-semibold">Tax Rate (%) <span class="text-danger">*</span></label>
            <input type="number" step="0.1" name="tax" class="form-control" value="10" required>
        </div>
        <div class="col-4">
            <label class="form-label small fw-semibold">Invoice Prefix <span class="text-danger">*</span></label>
            <input type="text" name="invoice_prefix" class="form-control" value="INV" required>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <span class="small text-muted">Step 5 of 6</span>
        <button type="submit" class="btn btn-primary fw-semibold">Complete Setup <i class="bi bi-arrow-right ms-1"></i></button>
    </div>
</form>
@endsection
