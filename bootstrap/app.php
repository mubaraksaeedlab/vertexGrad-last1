<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'frontend.locale' => \App\Http\Middleware\SetFrontendLocale::class,
            'backend.locale' => \App\Http\Middleware\SetBackendLocale::class,
            'frontend.verified.policy' => \App\Http\Middleware\EnsureFrontendVerificationMatchesPolicy::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if (
                $request->is('admin*') ||
                $request->is('manager*') ||
                $request->is('Supervisior*') ||
                $request->is('supervisior*') ||
                $request->is('supervisor*')
            ) {
                return route('admin.login.show');
            }

            return route('login.show');
        });

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();