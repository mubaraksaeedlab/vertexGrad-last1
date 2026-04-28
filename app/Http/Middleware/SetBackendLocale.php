<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetBackendLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'ar'];

        $locale = session(
            'backend_locale',
            $request->cookie('backend_locale', config('app.locale'))
        );

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
        }

        session(['backend_locale' => $locale]);

        App::setLocale($locale);

        return $next($request);
    }
}