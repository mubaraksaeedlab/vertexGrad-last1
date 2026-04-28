<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function allGrouped()
    {
        return Setting::orderBy('group')
            ->orderBy('id')
            ->get()
            ->groupBy('group');
    }

    public function updateMany(array $data): void
    {
        $settings = Setting::all()->keyBy('key');

        foreach ($settings as $key => $setting) {
            $value = $data[$key] ?? null;

            if ($setting->type === 'boolean') {
                $value = array_key_exists($key, $data) ? '1' : '0';
            }

            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            $setting->update([
                'value' => $value,
            ]);

            Cache::forget("setting.{$key}");
        }
    }
}