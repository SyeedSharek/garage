<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = array_keys(config('app.available_locales', ['en', 'ar']));
        $locale = $request->get('locale')
            ?? Session::get('locale')
            ?? $request->header('Accept-Language')
            ?? config('app.locale', 'en');

        // Extract language code from Accept-Language header (e.g., "ar-SA" -> "ar")
        if (strlen($locale) > 2) {
            $locale = substr($locale, 0, 2);
        }

        // Validate locale is available
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}

