<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elec = Category::where('code', 'CAT-ELEC')->first()->id;
        $fash = Category::where('code', 'CAT-FASH')->first()->id;
        $food = Category::where('code', 'CAT-FOOD')->first()->id;
        $stat = Category::where('code', 'CAT-STAT')->first()->id;
        $home = Category::where('code', 'CAT-HOME')->first()->id;

        $sup1 = Supplier::where('supplier_code', 'SUP-0001')->first()->id;
        $sup2 = Supplier::where('supplier_code', 'SUP-0002')->first()->id;
        $sup3 = Supplier::where('supplier_code', 'SUP-0003')->first()->id;
        $sup4 = Supplier::where('supplier_code', 'SUP-0004')->first()->id;
        $sup5 = Supplier::where('supplier_code', 'SUP-0005')->first()->id;

        $products = [
            // Electronics (4 products)
            ['product_code' => 'PRD-0001', 'barcode' => '89912340001', 'name' => 'Wireless Bluetooth Earbuds Pro', 'category_id' => $elec, 'supplier_id' => $sup1, 'purchase_price' => 25.00, 'selling_price' => 45.00, 'stock' => 50, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'Noise-canceling Bluetooth earbuds with charging case.'],
            ['product_code' => 'PRD-0002', 'barcode' => '89912340002', 'name' => 'Ultra Slim Powerbank 10000mAh', 'category_id' => $elec, 'supplier_id' => $sup1, 'purchase_price' => 12.50, 'selling_price' => 24.99, 'stock' => 30, 'min_stock' => 8, 'unit' => 'pcs', 'description' => 'Fast-charging USB-C dual powerbank.'],
            ['product_code' => 'PRD-0003', 'barcode' => '89912340003', 'name' => 'Ergonomic RGB Gaming Mouse', 'category_id' => $elec, 'supplier_id' => $sup1, 'purchase_price' => 15.00, 'selling_price' => 29.50, 'stock' => 3, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'High-precision optical gaming mouse.'],
            ['product_code' => 'PRD-0004', 'barcode' => '89912340004', 'name' => 'Smart Fitness Band Watch', 'category_id' => $elec, 'supplier_id' => $sup1, 'purchase_price' => 30.00, 'selling_price' => 59.99, 'stock' => 15, 'min_stock' => 4, 'unit' => 'pcs', 'description' => 'Heart rate monitor, step tracker, and water resistant.'],

            // Fashion (3 products)
            ['product_code' => 'PRD-0005', 'barcode' => '89912340005', 'name' => 'Classic Cotton Oxford Shirt', 'category_id' => $fash, 'supplier_id' => $sup2, 'purchase_price' => 10.00, 'selling_price' => 22.00, 'stock' => 40, 'min_stock' => 10, 'unit' => 'pcs', 'description' => '100% breathable premium cotton shirt.'],
            ['product_code' => 'PRD-0006', 'barcode' => '89912340006', 'name' => 'Slim Fit Denim Jeans Pants', 'category_id' => $fash, 'supplier_id' => $sup2, 'purchase_price' => 18.00, 'selling_price' => 38.00, 'stock' => 25, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'Durable stretch denim trousers.'],
            ['product_code' => 'PRD-0007', 'barcode' => '89912340007', 'name' => 'Leather Casual Sneakers', 'category_id' => $fash, 'supplier_id' => $sup2, 'purchase_price' => 28.00, 'selling_price' => 65.00, 'stock' => 2, 'min_stock' => 4, 'unit' => 'pair', 'description' => 'Handcrafted synthetic leather sneakers.'],

            // Food & Beverages (1 product)
            ['product_code' => 'PRD-0008', 'barcode' => '89912340008', 'name' => 'Premium Roasted Arabica Coffee 250g', 'category_id' => $food, 'supplier_id' => $sup3, 'purchase_price' => 4.50, 'selling_price' => 9.50, 'stock' => 60, 'min_stock' => 12, 'unit' => 'pack', 'description' => 'Single origin roasted coffee beans.'],

            // Stationery (1 product)
            ['product_code' => 'PRD-0009', 'barcode' => '89912340013', 'name' => 'Hardcover Journal Notebook A5', 'category_id' => $stat, 'supplier_id' => $sup4, 'purchase_price' => 2.50, 'selling_price' => 5.99, 'stock' => 80, 'min_stock' => 15, 'unit' => 'pcs', 'description' => '192 dotted pages thick paper notebook.'],

            // Home & Living (1 product)
            ['product_code' => 'PRD-0010', 'barcode' => '89912340018', 'name' => 'Ultrasonic Essential Oil Diffuser', 'category_id' => $home, 'supplier_id' => $sup5, 'purchase_price' => 11.00, 'selling_price' => 24.00, 'stock' => 14, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'Aromatherapy humidifier with 7 LED colors.'],
        ];

        foreach ($products as $prd) {
            Product::updateOrCreate(['product_code' => $prd['product_code']], $prd);
        }
    }
}
