<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoTranslateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $languages = ['en', 'es','pt']; // Idiomas soportados por tu aplicación
        $locale = $request->getPreferredLanguage($languages);
        app()->setLocale($locale);

        return $next($request);
    }
}
