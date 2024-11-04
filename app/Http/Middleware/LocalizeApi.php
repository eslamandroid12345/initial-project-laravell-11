<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalizeApi
{
    const LOCALES = ['en', 'ar'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');
        $locale = in_array($locale, self::LOCALES, true) ? $locale : config('app.fallback_locale');
        app()->setLocale($locale);

        return $next($request);
    }
}
