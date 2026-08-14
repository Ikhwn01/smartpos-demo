<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Store Settings
            ['key' => 'store_name', 'value' => 'SmartPOS Megastore', 'group' => 'store'],
            ['key' => 'store_logo', 'value' => '', 'group' => 'store'],
            ['key' => 'store_address', 'value' => 'Jl. Merdeka No. 45, Jakarta Selatan 12930', 'group' => 'store'],
            ['key' => 'store_phone', 'value' => '+62 812-3456-7890', 'group' => 'store'],
            ['key' => 'store_email', 'value' => 'info@smartpos-store.com', 'group' => 'store'],
            ['key' => 'store_website', 'value' => 'https://smartpos.com', 'group' => 'store'],
            ['key' => 'currency', 'value' => '$', 'group' => 'store'],
            ['key' => 'tax', 'value' => '10', 'group' => 'store'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'group' => 'store'],

            // System Settings
            ['key' => 'date_format', 'value' => 'Y-m-d', 'group' => 'system'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'group' => 'system'],
            ['key' => 'currency_format', 'value' => 'prefix', 'group' => 'system'],
            ['key' => 'items_per_page', 'value' => '10', 'group' => 'system'],
            ['key' => 'default_language', 'value' => 'en', 'group' => 'system'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
