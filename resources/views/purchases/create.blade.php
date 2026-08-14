@extends('layouts.app')

@section('title', 'New Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Create Purchase Order</h4>
        <p class="text-muted small mb-0">Record incoming stock order. Stocks will automatically increase upon submission.</p>
    </div>
    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to PO List</a>
</div>

<form action="{{ route('purchases.store') }}" method="POST" id="po-form">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-custom mb-4">
                <div class="card-header">Supplier & Order Details</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->company }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="debit_card">Debit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Order Items</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item"><i class="bi bi-plus-lg me-1"></i> Add Product Item</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0" id="po-items-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Product</th>
                                    <th style="width: 20%;">Qty</th>
                                    <th style="width: 25%;">Cost Price</th>
                                    <th style="width: 15%;" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="po-item-row">
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-sm product-select" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $prd)
                                            <option value="{{ $prd->id }}" data-price="{{ $prd->purchase_price }}">{{ $prd->name }} (Stock: {{ $prd->stock }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control form-control-sm item-qty" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[0][purchase_price]" class="form-control form-control-sm item-cost" value="0.00" required>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-light text-danger btn-remove-row"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom mb-4">
                <div class="card-header">Payment & Notes</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Discount Amount</label>
                        <input type="number" step="0.01" name="discount" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tax Amount</label>
                        <input type="number" step="0.01" name="tax" class="form-control" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Order Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Supplier delivery note..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Receive Order & Update Stock
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;
    const tableBody = document.querySelector('#po-items-table tbody');

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.className = 'po-item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select form-select-sm product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $prd)
                    <option value="{{ $prd->id }}" data-price="{{ $prd->purchase_price }}">{{ $prd->name }} (Stock: {{ $prd->stock }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control form-control-sm item-qty" value="1" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][purchase_price]" class="form-control form-control-sm item-cost" value="0.00" required>
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-light text-danger btn-remove-row"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);
        bindRowEvents(newRow);
        rowIndex++;
    });

    function bindRowEvents(row) {
        const select = row.querySelector('.product-select');
        const costInput = row.querySelector('.item-cost');
        select.addEventListener('change', function () {
            const selectedOpt = select.options[select.selectedIndex];
            const price = selectedOpt.getAttribute('data-price') || 0;
            costInput.value = parseFloat(price).toFixed(2);
        });

        row.querySelector('.btn-remove-row').addEventListener('click', function () {
            if (document.querySelectorAll('.po-item-row').length > 1) {
                row.remove();
            }
        });
    }

    document.querySelectorAll('.po-item-row').forEach(bindRowEvents);
});
</script>
@endpush
