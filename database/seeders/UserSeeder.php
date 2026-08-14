<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+62 811-0000-1111',
                'status' => 'active',
            ]
        );

        // Staff user
        User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff Cashier',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '+62 822-0000-2222',
                'status' => 'active',
            ]
        );
    }
}
