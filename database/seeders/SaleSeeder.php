<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $saleService = app(SaleService::class);
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first() ?? $admin;
        $customers = Customer::all();
        $products = Product::all();

        if ($products->count() >= 5 && $admin) {
            // Sale 1 - Today
            $saleService->createSale([
                'customer_id' => $customers[1]->id ?? null,
                'payment_method' => 'cash',
                'discount' => 2.00,
                'tax_percent' => 10,
                'paid_amount' => 100.00,
                'notes' => 'Store sale transaction',
                'items' => [
                    ['product_id' => $products[0]->id, 'quantity' => 1, 'price' => $products[0]->selling_price, 'discount' => 0],
                    ['product_id' => $products[4]->id, 'quantity' => 2, 'price' => $products[4]->selling_price, 'discount' => 1.00],
                ],
            ], $staff->id);

            // Sale 2 - Yesterday
            $saleService->createSale([
                'customer_id' => $customers[2]->id ?? null,
                'payment_method' => 'e_wallet',
                'discount' => 0,
                'tax_percent' => 10,
                'paid_amount' => 50.00,
                'notes' => 'E-Wallet POS Checkout',
                'items' => [
                    ['product_id' => $products[1]->id, 'quantity' => 1, 'price' => $products[1]->selling_price, 'discount' => 0],
                    ['product_id' => $products[8]->id, 'quantity' => 2, 'price' => $products[8]->selling_price, 'discount' => 0],
                ],
            ], $admin->id);

            // Sale 3 - Walk-in
            $saleService->createSale([
                'customer_id' => $customers[0]->id ?? null,
                'payment_method' => 'debit_card',
                'discount' => 0,
                'tax_percent' => 10,
                'paid_amount' => 50.00,
                'notes' => 'Walk-in customer sale',
                'items' => [
                    ['product_id' => $products[2]->id, 'quantity' => 1, 'price' => $products[2]->selling_price, 'discount' => 0],
                    ['product_id' => $products[7]->id, 'quantity' => 2, 'price' => $products[7]->selling_price, 'discount' => 0],
                ],
            ], $staff->id);
        }
    }
}
