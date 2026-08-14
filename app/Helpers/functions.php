<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get or set setting value from database cache.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::getByKey($key, $default);
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format number with currency symbol from setting.
     */
    function currency_format(float|int $amount): string
    {
        $currency = setting('currency', '$');
        $format = setting('currency_format', 'prefix'); // prefix or suffix

        $formatted = number_format($amount, 2, '.', ',');

        if ($format === 'suffix') {
            return $formatted . ' ' . $currency;
        }

        return $currency . ' ' . $formatted;
    }
}

if (!function_exists('date_format_custom')) {
    /**
     * Format date using store system setting.
     */
    function date_format_custom($date, string $format = null): string
    {
        if (!$date) return '-';
        $fmt = $format ?? setting('date_format', 'Y-m-d');
        return \Carbon\Carbon::parse($date)->format($fmt);
    }
}
