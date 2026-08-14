<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $perPage = setting('items_per_page', 10);
        $sales = $query->orderByDesc('sale_date')->paginate($perPage)->withQueryString();

        $customers = Customer::all();
        $cashiers = User::all();

        return view('sales.index', compact('sales', 'customers', 'cashiers'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.invoice', compact('sale'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.print', compact('sale'));
    }

    public function downloadPdf(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.print', ['sale' => $sale, 'isPdf' => true]);
    }
}
