@extends('layouts.installer')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-3-circle-fill text-primary me-2"></i> Step 3: Create Database Tables</h5>
<p class="text-muted small mb-4">Click below to generate all relational database tables and populate standard retail demo catalog data.</p>

<div class="p-3 bg-light rounded border mb-4">
    <div class="fw-bold small text-dark mb-1"><i class="bi bi-database-check text-success me-1"></i> Tables & Seeders to be Created:</div>
    <div class="small text-muted">users, roles, categories, suppliers, customers, products, purchases, purchase_items, sales, sale_items, expenses, expense_categories, inventory_transactions, notifications, settings</div>
</div>

<form action="{{ route('install.step3.run') }}" method="POST">
    @csrf
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('install.step2') }}" class="btn btn-outline-secondary">Back</a>
        <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-play-circle me-1"></i> Run Migrations & Seeders</button>
    </div>
</form>
@endsection
