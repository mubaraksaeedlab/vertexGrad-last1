<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetFrontendLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'ar'];

        $locale = session(
            'frontend_locale',
            $request->cookie('frontend_locale', config('app.locale'))
        );

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
        }

        session(['frontend_locale' => $locale]);

        App::setLocale($locale);

        return $next($request);
    }
}