<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\PurchaseService;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $purchaseService = app(PurchaseService::class);
        $admin = User::where('role', 'admin')->first();
        $suppliers = Supplier::all();
        $products = Product::all();

        if ($suppliers->count() > 0 && $products->count() > 0 && $admin) {
            // Purchase 1
            $purchaseService->createPurchase([
                'supplier_id' => $suppliers[0]->id,
                'purchase_date' => now()->subDays(10)->toDateString(),
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'discount' => 10,
                'tax' => 0,
                'notes' => 'Stock replenishment PO-001',
                'items' => [
                    ['product_id' => $products[0]->id, 'quantity' => 20, 'purchase_price' => $products[0]->purchase_price],
                    ['product_id' => $products[1]->id, 'quantity' => 15, 'purchase_price' => $products[1]->purchase_price],
                ],
            ], $admin->id);

            // Purchase 2
            $purchaseService->createPurchase([
                'supplier_id' => $suppliers[2]->id,
                'purchase_date' => now()->subDays(5)->toDateString(),
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'discount' => 0,
                'tax' => 0,
                'notes' => 'Fresh stock shipment',
                'items' => [
                    ['product_id' => $products[8]->id, 'quantity' => 30, 'purchase_price' => $products[8]->purchase_price],
                    ['product_id' => $products[9]->id, 'quantity' => 25, 'purchase_price' => $products[9]->purchase_price],
                ],
            ], $admin->id);
        }
    }
}
