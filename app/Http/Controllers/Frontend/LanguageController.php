<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
public function switch(string $locale)
{
    if (! in_array($locale, ['en', 'ar'], true)) {
        abort(404);
    }

    session(['frontend_locale' => $locale]);

    return redirect()->back()->withCookie(
        cookie('frontend_locale', $locale, 60 * 24 * 365)
    );
}
}