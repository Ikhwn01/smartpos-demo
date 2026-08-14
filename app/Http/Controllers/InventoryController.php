<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status === 'in_stock') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $perPage = setting('items_per_page', 10);
        $products = $query->paginate($perPage)->withQueryString();
        $categories = Category::where('status', true)->get();

        $transactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->limit(15)
            ->get();

        return view('inventory.index', compact('products', 'categories', 'transactions'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        $this->inventoryService->adjustStock(
            $product,
            $request->quantity,
            $request->type,
            auth()->id(),
            'ADJ-' . time(),
            $request->notes
        );

        return back()->with('success', "Stock for '{$product->name}' adjusted successfully.");
    }

    public function history(Request $request)
    {
        $query = InventoryTransaction::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $perPage = setting('items_per_page', 15);
        $history = $query->latest()->paginate($perPage)->withQueryString();

        return view('inventory.history', compact('history'));
    }
}
