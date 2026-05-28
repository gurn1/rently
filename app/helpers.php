<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            $setting = \App\Models\Setting::where('key', $key)->first();
            if ($setting && $setting->value !== null) {
                return $setting->value;
            }
        } catch (\Exception $e) {
            // Database not available — fall through to default
        }

        return match($key) {
            'stripe_key'            => config('cashier.key'),
            'stripe_secret'         => config('cashier.secret'),
            'stripe_webhook_secret' => config('cashier.webhook.secret'),
            default                 => $default,
        };
    }
}