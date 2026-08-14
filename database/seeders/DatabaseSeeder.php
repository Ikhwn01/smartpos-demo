<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            ExpenseCategorySeeder::class,
            PurchaseSeeder::class,
            SaleSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
