<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService)
    {
    }

    public function index()
    {
        $settings = $this->settingService->allGrouped();

        $groupLabels = [
            'general' => 'General',
            'project' => 'Project',
            'notifications' => 'Notifications',
            'audit' => 'Audit Logs',
            'scanner' => 'Scanner',
            'security' => 'Security',
            'appearance' => 'Appearance',
            'branding' => 'Branding',
        ];

        return view('admin.settings.index', compact('settings', 'groupLabels'));
    }

   public function update(Request $request)
{
    $settings = $this->settingService->allGrouped()->flatten();

    $payload = [];
    $oldValues = [];

    foreach ($settings as $setting) {
        $oldValues[$setting->key] = $setting->value;

        if ($setting->type === 'boolean') {
            $payload[$setting->key] = $request->has($setting->key) ? '1' : '0';
            continue;
        }

        if ($setting->type === 'image') {
            if ($request->hasFile($setting->key)) {
                $file = $request->file($setting->key);
                $path = $file->store('settings', 'public');
                $payload[$setting->key] = $path;
            } else {
                $payload[$setting->key] = $setting->value;
            }

            continue;
        }

        $payload[$setting->key] = $request->input($setting->key);
    }

    $this->settingService->updateMany($payload);

    $newValues = [];
    foreach ($settings as $setting) {
        $newValues[$setting->key] = $payload[$setting->key] ?? null;
    }

    if (class_exists(\App\Services\AuditLogService::class)) {
        \App\Services\AuditLogService::log(
            event: 'updated',
            description: 'Updated system settings',
            category: 'settings',
            oldValues: $oldValues,
            newValues: $newValues,
            properties: [
                'updated_keys_count' => count($payload),
            ]
        );
    }

    return redirect()
        ->route('admin.settings.index')
        ->with('success', 'Settings updated successfully.');
}
}