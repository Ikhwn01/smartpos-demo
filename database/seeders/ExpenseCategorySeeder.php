<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electricity', 'description' => 'Utility power and energy bills.'],
            ['name' => 'Internet & Phone', 'description' => 'Broadband and communication bills.'],
            ['name' => 'Transportation', 'description' => 'Fuel, shipping, and delivery fees.'],
            ['name' => 'Staff Salary', 'description' => 'Employee wages and bonuses.'],
            ['name' => 'Store Rent', 'description' => 'Monthly facility lease.'],
            ['name' => 'Maintenance', 'description' => 'Equipment repair and cleaning supplies.'],
            ['name' => 'Marketing', 'description' => 'Advertisements and promotional materials.'],
            ['name' => 'Other', 'description' => 'Miscellaneous expenses.'],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
