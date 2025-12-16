<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $firstSegment = $request->segment(1);

        $availableLocales = config('localization.availableLocales');
        $localeDefault = config('app.locale') ?: config('app.fallback_locale');

        $locale = in_array($firstSegment, $availableLocales) ? $firstSegment : $localeDefault;

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
