@extends('layouts.app')

@section('title', __('messages.products'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">{{ __('messages.product_catalog') }}</h4>
        <p class="text-muted small mb-0">{{ __('messages.inventory_catalog') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.import') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i> {{ __('messages.import_excel') }}
        </a>
        <a href="{{ route('products.export.csv') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i> {{ __('messages.export_csv') }}
        </a>
        <a href="{{ route('products.export.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> {{ __('messages.export_pdf') }}
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> {{ __('messages.add_product') }}
        </a>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('messages.search_product_ph') }}" value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_categories') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_stock_statuses') }}</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>{{ __('messages.in_stock') }}</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>{{ __('messages.low_stock') }}</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>{{ __('messages.out_of_stock') }}</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter me-1"></i> {{ __('messages.filter') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-light border btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.code_barcode') }}</th>
                        <th>{{ __('messages.product_details') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.cost_price') }}</th>
                        <th>{{ __('messages.selling_price') }}</th>
                        <th>{{ __('messages.stock_level') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace">{{ $product->product_code }}</span>
                            @if($product->barcode)
                            <div class="small text-muted font-monospace"><i class="bi bi-barcode me-1"></i>{{ $product->barcode }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="Img" class="rounded me-2 border" width="40" height="40" style="object-fit: cover;">
                                <div>
                                    <a href="{{ route('products.show', $product->id) }}" class="fw-semibold text-decoration-none text-dark">{{ $product->name }}</a>
                                    <div class="small text-muted">{{ $product->unit }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary border">{{ $product->category->name ?? 'Unassigned' }}</span></td>
                        <td class="text-muted">{{ currency_format($product->purchase_price) }}</td>
                        <td class="fw-bold text-primary">{{ currency_format($product->selling_price) }}</td>
                        <td>
                            <span class="fw-bold {{ $product->stock <= $product->min_stock ? 'text-danger' : '' }}">{{ $product->stock }} {{ $product->unit }}</span>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger ms-1">{{ __('messages.out_of_stock') }}</span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="badge bg-warning text-dark ms-1">{{ __('messages.low_stock') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge bg-success">{{ __('messages.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-light border" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light border text-danger btn-delete-confirm" data-name="{{ $product->name }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No products found.</td>
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
@endsection
