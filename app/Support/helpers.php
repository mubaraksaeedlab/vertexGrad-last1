<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", now()->addHours(6), function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();

            return $setting ? $setting->casted_value : $default;
        });
    }
}