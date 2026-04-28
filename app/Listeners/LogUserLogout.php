<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\AuditLogService;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (!$user) return;

        AuditLogService::log(
            event: 'logout',
            description: 'User logged out: ' . ($user->name ?? $user->username),
            category: 'auth',
            subject: $user,
            properties: [
                'email' => $user->email,
                'role'  => $user->role ?? null,
            ]
        );
    }
}