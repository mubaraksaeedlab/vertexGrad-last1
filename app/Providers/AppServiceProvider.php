<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Investor;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $login = (string) $request->input('login_id');

            return Limit::perMinute(5)->by(strtolower($login).'|'.$request->ip());
        });

        // Route model binding
        Route::bind('investor', function ($value) {
            return Investor::withTrashed()
                ->where('user_id', $value)
                ->firstOrFail();
        });

        // Middleware alias
        Route::aliasMiddleware('role', RoleMiddleware::class);

        // Load admin routes
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));

        // Permission Blade Directive
        Blade::if('permission', function ($permission) {
            $user = auth('admin')->user() ?? auth('web')->user();

            return $user && $user->hasPermission($permission);
        });
    }
}