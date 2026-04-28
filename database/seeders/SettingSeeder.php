<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'group' => 'general',
                'key' => 'platform_name',
                'label' => 'Platform Name',
                'value' => 'VertexGrad',
                'type' => 'text',
                'description' => 'The main name of the platform.',
                'is_public' => true,
            ],
            [
                'group' => 'general',
                'key' => 'platform_tagline',
                'label' => 'Platform Tagline',
                'value' => 'Where Innovation Meets Opportunity',
                'type' => 'text',
                'description' => 'Short subtitle shown in some public sections.',
                'is_public' => true,
            ],
            [
                'group' => 'general',
                'key' => 'default_timezone',
                'label' => 'Default Timezone',
                'value' => 'Asia/Riyadh',
                'type' => 'text',
                'description' => 'System timezone.',
                'is_public' => false,
            ],

            // Project
            [
                'group' => 'project',
                'key' => 'project_default_status',
                'label' => 'Default Project Status',
                'value' => 'Pending',
                'type' => 'select',
                'description' => 'Default status for newly created projects.',
                'options' => ['Pending', 'Active', 'Completed', 'Rejected'],
                'is_public' => false,
            ],
            [
                'group' => 'project',
                'key' => 'max_image_upload_size',
                'label' => 'Max Image Upload Size (KB)',
                'value' => '5120',
                'type' => 'number',
                'description' => 'Maximum image size allowed per file.',
                'is_public' => false,
            ],
            [
                'group' => 'project',
                'key' => 'max_video_upload_size',
                'label' => 'Max Video Upload Size (KB)',
                'value' => '51200',
                'type' => 'number',
                'description' => 'Maximum video size allowed per file.',
                'is_public' => false,
            ],

            // Notifications
            [
                'group' => 'notifications',
                'key' => 'enable_email_notifications',
                'label' => 'Enable Email Notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable sending email notifications.',
                'is_public' => false,
            ],
            [
                'group' => 'notifications',
                'key' => 'enable_in_app_notifications',
                'label' => 'Enable In-App Notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable notifications inside the platform.',
                'is_public' => false,
            ],

            [
                'group' => 'appearance',
                'key' => 'default_theme',
                'label' => 'Default Theme',
                'value' => 'light',
                'type' => 'select',
                'options' => ['light', 'dark'],
                'description' => 'Default theme for the platform.',
            ],
            [
                'group' => 'appearance',
                'key' => 'enable_theme_switcher',
                'label' => 'Enable Theme Switcher',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Allow users to switch between light and dark mode.',
            ],
            [
                'group' => 'appearance',
                'key' => 'auto_close_sidebar_mobile',
                'label' => 'Auto Close Sidebar on Mobile',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Automatically close sidebar on mobile devices.',
            ],

            // Audit
            [
                'group' => 'audit',
                'key' => 'enable_audit_logs',
                'label' => 'Enable Audit Logs',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable activity and audit logging across the platform.',
                'is_public' => false,
            ],
            [
                'group' => 'audit',
                'key' => 'audit_retention_days',
                'label' => 'Audit Retention Days',
                'value' => '180',
                'type' => 'number',
                'description' => 'Number of days to retain audit logs.',
                'is_public' => false,
            ],

            // Scanner
            [
                'group' => 'scanner',
                'key' => 'scanner_enabled',
                'label' => 'Enable Scanner Integration',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable external scanner integration.',
                'is_public' => false,
            ],
            [
                'group' => 'scanner',
                'key' => 'scanner_base_url',
                'label' => 'Scanner Base URL',
                'value' => '',
                'type' => 'text',
                'description' => 'Base URL for scanner service.',
                'is_public' => false,
            ],
            [
                'group' => 'scanner',
                'key' => 'scanner_min_score',
                'label' => 'Minimum Scanner Score',
                'value' => '70',
                'type' => 'number',
                'description' => 'Minimum score considered acceptable.',
                'is_public' => false,
            ],

            // Security
            [
                'group' => 'security',
                'key' => 'max_login_attempts',
                'label' => 'Max Login Attempts',
                'value' => '5',
                'type' => 'number',
                'description' => 'Maximum failed login attempts before lockout.',
                'is_public' => false,
            ],
            [
                'group' => 'security',
                'key' => 'session_timeout_minutes',
                'label' => 'Session Timeout (Minutes)',
                'value' => '120',
                'type' => 'number',
                'description' => 'Automatic session timeout in minutes.',
                'is_public' => false,
            ],

            // Appearance
            [
                'group' => 'appearance',
                'key' => 'primary_color',
                'label' => 'Primary Color',
                'value' => '#1D4ED8',
                'type' => 'text',
                'description' => 'Main platform color.',
                'is_public' => true,
            ],
            [
                'group' => 'appearance',
                'key' => 'default_theme_mode',
                'label' => 'Default Theme Mode',
                'value' => 'light',
                'type' => 'select',
                'description' => 'Default theme mode.',
                'options' => ['light', 'dark'],
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'platform_logo',
                'label' => 'Platform Logo',
                'value' => '',
                'type' => 'image',
                'description' => 'Main platform logo used in the system.',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'platform_favicon',
                'label' => 'Platform Favicon',
                'value' => '',
                'type' => 'image',
                'description' => 'Browser tab icon.',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'admin_logo',
                'label' => 'Admin Panel Logo',
                'value' => '',
                'type' => 'image',
                'description' => 'Logo used in admin sidebar/header.',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
