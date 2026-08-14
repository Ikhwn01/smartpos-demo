<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['customer_code' => 'CUST-0001', 'name' => 'General Walk-in Customer', 'phone' => '-', 'email' => null, 'address' => 'Store Front Customer'],
            ['customer_code' => 'CUST-0002', 'name' => 'Ahmad Rizky', 'phone' => '+62 813-1111-2222', 'email' => 'ahmad.rizky@gmail.com', 'address' => 'Jl. Sudirman No. 12, Jakarta'],
            ['customer_code' => 'CUST-0003', 'name' => 'Anita Kusuma', 'phone' => '+62 815-2222-3333', 'email' => 'anita.k@yahoo.com', 'address' => 'Jl. Gatot Subroto No. 45, Jakarta'],
            ['customer_code' => 'CUST-0004', 'name' => 'Bambang Susilo', 'phone' => '+62 817-3333-4444', 'email' => 'bambang.s@outlook.com', 'address' => 'Jl. Diponegoro No. 8, Bandung'],
            ['customer_code' => 'CUST-0005', 'name' => 'Citra Lestari', 'phone' => '+62 819-4444-5555', 'email' => 'citra.lestari@gmail.com', 'address' => 'Jl. Pemuda No. 88, Semarang'],
            ['customer_code' => 'CUST-0006', 'name' => 'Doni Kurniawan', 'phone' => '+62 821-5555-6666', 'email' => 'doni.k@gmail.com', 'address' => 'Jl. Malioboro No. 19, Yogyakarta'],
            ['customer_code' => 'CUST-0007', 'name' => 'Eka Putri', 'phone' => '+62 823-6666-7777', 'email' => 'eka.putri@hotmail.com', 'address' => 'Jl. Basuki Rahmat No. 25, Surabaya'],
            ['customer_code' => 'CUST-0008', 'name' => 'Fajar Hidayat', 'phone' => '+62 856-7777-8888', 'email' => 'fajar.h@gmail.com', 'address' => 'Jl. Raya Bogor Km. 20, Depok'],
            ['customer_code' => 'CUST-0009', 'name' => 'Grace Natalia', 'phone' => '+62 857-8888-9999', 'email' => 'grace.natalia@gmail.com', 'address' => 'Jl. Serpong Raya No. 100, Tangerang'],
            ['customer_code' => 'CUST-0010', 'name' => 'Herman Tan', 'phone' => '+62 858-9999-0000', 'email' => 'herman.tan@gmail.com', 'address' => 'Jl. Asia Afrika No. 50, Bandung'],
        ];

        foreach ($customers as $cust) {
            Customer::updateOrCreate(['customer_code' => $cust['customer_code']], $cust);
        }
    }
}
