<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'user'])->where('status', 'completed');

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
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

        $sales = $query->orderByDesc('sale_date')->get();

        $summary = [
            'total_transactions' => $sales->count(),
            'total_sales' => $sales->sum('subtotal'),
            'total_discount' => $sales->sum('discount'),
            'total_tax' => $sales->sum('tax'),
            'net_sales' => $sales->sum('grand_total'),
        ];

        $customers = Customer::all();
        $cashiers = User::all();

        return view('reports.sales', compact('sales', 'summary', 'customers', 'cashiers'));
    }

    public function purchases(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items']);

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchases = $query->orderByDesc('purchase_date')->get();

        $summary = [
            'total_purchases' => $purchases->count(),
            'total_cost' => $purchases->sum('total_amount'),
        ];

        $suppliers = Supplier::all();

        return view('reports.purchases', compact('purchases', 'summary', 'suppliers'));
    }

    public function inventory(Request $request)
    {
        $products = Product::with(['category', 'supplier'])->get();

        $summary = [
            'total_items' => $products->count(),
            'total_stock' => $products->sum('stock'),
            'total_cost_value' => $products->sum(fn($p) => $p->stock * $p->purchase_price),
            'total_retail_value' => $products->sum(fn($p) => $p->stock * $p->selling_price),
        ];

        return view('reports.inventory', compact('products', 'summary'));
    }

    public function profit(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $profitData = $this->reportService->getProfitReport($dateFrom, $dateTo);

        return view('reports.profit', compact('profitData', 'dateFrom', 'dateTo'));
    }

    public function bestSelling(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $bestSellers = $this->reportService->getBestSellingProducts(20, $dateFrom, $dateTo);

        return view('reports.best-selling', compact('bestSellers', 'dateFrom', 'dateTo'));
    }

    public function exportCsv(Request $request, string $type)
    {
        $fileName = "report_{$type}_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($type, $request) {
            $file = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($file, ['Invoice', 'Date', 'Customer', 'Cashier', 'Payment Method', 'Subtotal', 'Discount', 'Tax', 'Grand Total']);
                $sales = Sale::with(['customer', 'user'])->get();
                foreach ($sales as $s) {
                    fputcsv($file, [
                        $s->invoice_number,
                        $s->sale_date,
                        $s->customer->name ?? 'Walk-in',
                        $s->user->name ?? '',
                        $s->payment_method,
                        $s->subtotal,
                        $s->discount,
                        $s->tax,
                        $s->grand_total,
                    ]);
                }
            } elseif ($type === 'inventory') {
                fputcsv($file, ['Code', 'Name', 'Category', 'Stock', 'Purchase Price', 'Selling Price', 'Stock Value (Cost)', 'Stock Value (Selling)']);
                $products = Product::with('category')->get();
                foreach ($products as $p) {
                    fputcsv($file, [
                        $p->product_code,
                        $p->name,
                        $p->category->name ?? '',
                        $p->stock,
                        $p->purchase_price,
                        $p->selling_price,
                        $p->stock * $p->purchase_price,
                        $p->stock * $p->selling_price,
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
