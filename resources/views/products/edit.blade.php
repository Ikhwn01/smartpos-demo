@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Product: {{ $product->name }}</h4>
        <p class="text-muted small mb-0">Update product specifications, prices, or inventory parameters.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
</div>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-custom mb-4">
                <div class="card-header">General Information</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Product Code (SKU) <span class="text-danger">*</span></label>
                            <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Barcode / EAN</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Preferred Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card card-custom mb-4">
                <div class="card-header">Pricing & Stock Inventory</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Purchase Price (Cost)</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ setting('currency', '$') }}</span>
                                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Selling Price (Retail)</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ setting('currency', '$') }}</span>
                                <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Min Warning Stock</label>
                            <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom mb-4">
                <div class="card-header">Product Image & Status</div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <img id="img-preview" src="{{ $product->image_url }}" class="img-fluid rounded border mb-2" style="max-height: 180px; width: 100%; object-fit: cover;">
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*" onchange="document.getElementById('img-preview').src = window.URL.createObjectURL(this.files[0])">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="status">Product Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-check-lg me-1"></i> Update Product</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
