<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = setting('items_per_page', 10);
        $products = $query->paginate($perPage)->withQueryString();

        $categories = Category::where('status', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $suppliers = Supplier::all();
        $nextCode = 'PRD-' . str_pad(Product::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('products.create', compact('categories', 'suppliers', 'nextCode'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['status'] = $request->has('status');

        $product = Product::create($data);

        // Check low stock warning if initial stock is low
        app(\App\Services\InventoryService::class)->checkLowStockNotification($product);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'inventoryTransactions.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                unlink(public_path('storage/' . $product->image));
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['status'] = $request->has('status');

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleItems()->count() > 0 || $product->purchaseItems()->count() > 0) {
            return back()->with('error', 'Cannot delete product because it has transaction history.');
        }

        if ($product->image && file_exists(public_path('storage/' . $product->image))) {
            unlink(public_path('storage/' . $product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function showImport()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:5048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count($row) < 9) continue;

            $code = trim($row[0]);
            $barcode = trim($row[1] ?? '');
            $name = trim($row[2]);
            $categoryName = trim($row[3]);
            $supplierName = trim($row[4] ?? '');
            $purchasePrice = floatval($row[5]);
            $sellingPrice = floatval($row[6]);
            $stock = intval($row[7]);
            $minStock = intval($row[8]);
            $unit = trim($row[9] ?? 'pcs');

            if (empty($code) || empty($name)) {
                $errors[] = "Row {$rowNum}: Code or Name is missing.";
                continue;
            }

            if (Product::where('product_code', $code)->exists()) {
                $errors[] = "Row {$rowNum}: Product code '{$code}' already exists.";
                continue;
            }

            $category = Category::firstOrCreate(['name' => $categoryName], [
                'code' => 'CAT-' . strtoupper(substr(md5($categoryName), 0, 4)),
            ]);

            $supplierId = null;
            if (!empty($supplierName)) {
                $supplier = Supplier::firstOrCreate(['name' => $supplierName], [
                    'supplier_code' => 'SUP-' . strtoupper(substr(md5($supplierName), 0, 4)),
                ]);
                $supplierId = $supplier->id;
            }

            Product::create([
                'product_code' => $code,
                'barcode' => $barcode,
                'name' => $name,
                'category_id' => $category->id,
                'supplier_id' => $supplierId,
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'stock' => $stock,
                'min_stock' => $minStock,
                'unit' => $unit,
                'status' => true,
            ]);

            $imported++;
        }

        fclose($handle);

        $message = "Successfully imported {$imported} products.";
        if (!empty($errors)) {
            return redirect()->route('products.index')->with('success', $message)->with('import_errors', $errors);
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        $path = public_path('templates/products_import_template.csv');
        if (!file_exists($path)) {
            abort(404, 'Template file not found.');
        }
        return response()->download($path, 'products_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportCsv()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $fileName = 'products_export_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product Code', 'Barcode', 'Name', 'Category', 'Supplier', 'Purchase Price', 'Selling Price', 'Stock', 'Min Stock', 'Unit', 'Status']);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->product_code,
                    $p->barcode,
                    $p->name,
                    $p->category->name ?? '',
                    $p->supplier->name ?? '',
                    $p->purchase_price,
                    $p->selling_price,
                    $p->stock,
                    $p->min_stock,
                    $p->unit,
                    $p->status ? 'Active' : 'Inactive',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return view('products.pdf', compact('products'));
    }
}
