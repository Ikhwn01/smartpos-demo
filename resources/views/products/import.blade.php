@extends('layouts.app')

@section('title', 'Import Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Import Products from Excel / CSV</h4>
        <p class="text-muted small mb-0">Upload a CSV or Excel file to bulk create catalog products.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
</div>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-cloud-upload me-2 text-primary"></i> Upload Import File</div>
            <div class="card-body">
                <form action="{{ route('products.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Excel / CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".csv, .xlsx, .txt" required>
                        <div class="form-text">Supported file formats: .csv, .xlsx (Max 5MB)</div>
                    </div>

                    <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-upload me-1"></i> Start Import Process</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 mb-4">
        <div class="card card-custom bg-light">
            <div class="card-header fw-bold"><i class="bi bi-info-circle me-2 text-info"></i> Instructions</div>
            <div class="card-body">
                <ol class="ps-3 mb-3 small text-muted">
                    <li>Download the sample CSV import template using the button below.</li>
                    <li>Fill in product details strictly following the header column order.</li>
                    <li>Save and upload the CSV file here.</li>
                </ol>
                <a href="{{ route('products.import.template') }}" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-download me-1"></i> Download CSV Template</a>
            </div>
        </div>
    </div>
</div>
@endsection
