<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => setting('store_name', 'SmartPOS Store'),
            'store_logo' => setting('store_logo', ''),
            'store_address' => setting('store_address', '123 Commercial Boulevard, Suite 100'),
            'store_phone' => setting('store_phone', '+1 (555) 019-2834'),
            'store_email' => setting('store_email', 'contact@smartpos.local'),
            'store_website' => setting('store_website', 'https://smartpos.local'),
            'currency' => setting('currency', '$'),
            'tax' => setting('tax', '0'),
            'invoice_prefix' => setting('invoice_prefix', 'INV'),
            'date_format' => setting('date_format', 'Y-m-d'),
            'timezone' => setting('timezone', 'UTC'),
            'currency_format' => setting('currency_format', 'prefix'),
            'items_per_page' => setting('items_per_page', '10'),
            'default_language' => setting('default_language', 'en'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:50',
            'store_email' => 'nullable|email|max:255',
            'store_website' => 'nullable|url|max:255',
            'currency' => 'required|string|max:10',
            'tax' => 'required|numeric|min:0|max:100',
            'invoice_prefix' => 'required|string|max:20',
            'date_format' => 'required|string',
            'timezone' => 'required|string',
            'currency_format' => 'required|in:prefix,suffix',
            'items_per_page' => 'required|integer|min:5|max:100',
            'default_language' => 'required|in:en,id',
            'store_logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('settings', 'public');
            Setting::setByKey('store_logo', $logoPath, 'store');
        }

        unset($data['store_logo']);

        foreach ($data as $key => $value) {
            $group = in_array($key, ['date_format', 'timezone', 'currency_format', 'items_per_page', 'default_language']) ? 'system' : 'store';
            Setting::setByKey($key, $value, $group);
        }

        return redirect()->route('settings.index')->with('success', 'Store and system settings saved successfully.');
    }
}
