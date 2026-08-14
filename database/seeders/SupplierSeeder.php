<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['supplier_code' => 'SUP-0001', 'name' => 'Global Tech Distribution', 'company' => 'PT Tech World', 'phone' => '+62 21-555-0101', 'email' => 'sales@techworld.com', 'address' => 'Jakarta Industrial Zone Block A-12', 'contact_person' => 'Budi Santoso'],
            ['supplier_code' => 'SUP-0002', 'name' => 'Nusantara Fashion Group', 'company' => 'CV Nusantara Style', 'phone' => '+62 22-444-0202', 'email' => 'info@nusantarastyle.co.id', 'address' => 'Bandung Textile Hub No. 88', 'contact_person' => 'Siti Rahma'],
            ['supplier_code' => 'SUP-0003', 'name' => 'Agro Food Supply Inc.', 'company' => 'PT Agro Premier', 'phone' => '+62 31-333-0303', 'email' => 'order@agropremier.com', 'address' => 'Surabaya Trade Center Lt. 3', 'contact_person' => 'Hendra Wijaya'],
            ['supplier_code' => 'SUP-0004', 'name' => 'PaperCraft Stationeries', 'company' => 'CV PaperCraft', 'phone' => '+62 21-777-0404', 'email' => 'support@papercraft.id', 'address' => 'Tangerang Office Park B-5', 'contact_person' => 'Dewi Lestari'],
            ['supplier_code' => 'SUP-0005', 'name' => 'Living Comfort Supplies', 'company' => 'PT Comfort Home', 'phone' => '+62 21-888-0505', 'email' => 'sales@comforthome.com', 'address' => 'Bekasi Logistics Park Kav. 14', 'contact_person' => 'Agus Pratama'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(['supplier_code' => $sup['supplier_code']], $sup);
        }
    }
}
