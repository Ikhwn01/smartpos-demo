<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $elec = ExpenseCategory::where('name', 'Electricity')->first();
        $net = ExpenseCategory::where('name', 'Internet & Phone')->first();
        $maint = ExpenseCategory::where('name', 'Maintenance')->first();

        if ($admin && $elec && $net) {
            Expense::create([
                'expense_number' => 'EXP-20260801-0001',
                'expense_category_id' => $elec->id,
                'user_id' => $admin->id,
                'title' => 'Monthly Store Electricity Bill',
                'description' => 'Power utility bill for main store branch.',
                'amount' => 150.00,
                'expense_date' => now()->startOfMonth()->toDateString(),
                'payment_method' => 'bank_transfer',
            ]);

            Expense::create([
                'expense_number' => 'EXP-20260805-0002',
                'expense_category_id' => $net->id,
                'user_id' => $admin->id,
                'title' => 'High Speed Fiber Broadband Internet',
                'description' => 'POS terminal broadband internet connection.',
                'amount' => 65.00,
                'expense_date' => now()->subDays(9)->toDateString(),
                'payment_method' => 'bank_transfer',
            ]);

            if ($maint) {
                Expense::create([
                    'expense_number' => 'EXP-20260810-0003',
                    'expense_category_id' => $maint->id,
                    'user_id' => $admin->id,
                    'title' => 'Barcode Scanner & Printer Maintenance',
                    'description' => 'Thermal printer calibration & cleaning.',
                    'amount' => 35.00,
                    'expense_date' => now()->subDays(4)->toDateString(),
                    'payment_method' => 'cash',
                ]);
            }
        }
    }
}
