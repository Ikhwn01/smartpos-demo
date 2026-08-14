<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        $categories = Category::where('status', true)->get();
        $customers = Customer::all();
        $taxPercent = setting('tax', 0);
        $currencySymbol = setting('currency', '$');

        return view('pos.index', compact('categories', 'customers', 'taxPercent', 'currencySymbol'));
    }

    public function searchProducts(Request $request)
    {
        $query = Product::where('status', true);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->limit(24)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'product_code' => $product->product_code,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'price' => floatval($product->selling_price),
                'stock' => $product->stock,
                'unit' => $product->unit,
                'image_url' => $product->image_url,
                'stock_status' => $product->stock_status,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|string|in:cash,bank_transfer,e_wallet,debit_card,credit_card',
            'discount' => 'nullable|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            $sale = $this->saleService->createSale($request->all(), auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Sale transaction completed successfully.',
                'sale_id' => $sale->id,
                'invoice_url' => route('sales.invoice', $sale->id),
                'print_url' => route('sales.print', $sale->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
