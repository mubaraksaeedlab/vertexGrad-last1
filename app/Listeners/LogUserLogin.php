<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\AuditLogService;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        AuditLogService::log(
            event: 'login',
            description: 'User logged in: ' . ($user->name ?? $user->username),
            category: 'auth',
            subject: $user,
            properties: [
                'email' => $user->email,
                'role'  => $user->role ?? null,
            ]
        );
    }
}