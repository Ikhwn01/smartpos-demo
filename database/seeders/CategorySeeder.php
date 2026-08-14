<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics & Gadgets', 'code' => 'CAT-ELEC', 'description' => 'Electronic devices, accessories, and audio gear.'],
            ['name' => 'Fashion & Apparel', 'code' => 'CAT-FASH', 'description' => 'Clothing, shoes, bags, and fashion accessories.'],
            ['name' => 'Food & Beverages', 'code' => 'CAT-FOOD', 'description' => 'Groceries, snacks, drinks, and fresh produce.'],
            ['name' => 'Stationery & Office', 'code' => 'CAT-STAT', 'description' => 'Office supplies, paper, pens, and desk organization.'],
            ['name' => 'Home & Living', 'code' => 'CAT-HOME', 'description' => 'Kitchenware, home decor, and cleaning supplies.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['code' => $cat['code']], $cat);
        }
    }
}
