<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set the locale from the first URL segment (e.g. /en), otherwise keep the default.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (in_array($locale, config('restaurant.locales'), true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
