<?php

namespace App\Services;

use App\Models\LoginActivity;
use Illuminate\Http\Request;

class SecurityActivityLogger
{
    public static function log(?int $userId, string $event, Request $request, bool $isSuccess = true, array $meta = []): void
    {
        $userAgent = (string) $request->userAgent();

        LoginActivity::create([
            'user_id'    => $userId,
            'event'      => $event,
            'is_success' => $isSuccess,
            'ip_address' => $request->ip(),
            'device'     => self::detectDevice($userAgent),
            'browser'    => self::detectBrowser($userAgent),
            'os'         => self::detectOs($userAgent),
            'session_id' => $request->session()->getId(),
            'user_agent' => $userAgent,
            'meta'       => !empty($meta) ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

    public static function detectDevice(string $ua): string
    {
        return str_contains(strtolower($ua), 'mobile') ? 'Mobile' : 'Desktop';
    }

    public static function detectBrowser(string $ua): string
    {
        $ua = strtolower($ua);

        return match (true) {
            str_contains($ua, 'edg') => 'Edge',
            str_contains($ua, 'chrome') => 'Chrome',
            str_contains($ua, 'firefox') => 'Firefox',
            str_contains($ua, 'safari') && !str_contains($ua, 'chrome') => 'Safari',
            default => 'Unknown',
        };
    }

    public static function detectOs(string $ua): string
    {
        $ua = strtolower($ua);

        return match (true) {
            str_contains($ua, 'windows') => 'Windows',
            str_contains($ua, 'mac os') || str_contains($ua, 'macintosh') => 'macOS',
            str_contains($ua, 'android') => 'Android',
            str_contains($ua, 'iphone') || str_contains($ua, 'ipad') => 'iOS',
            str_contains($ua, 'linux') => 'Linux',
            default => 'Unknown',
        };
    }
}