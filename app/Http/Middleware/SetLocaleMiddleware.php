<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle locale configuration on every web request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', setting('default_language', 'en'));

        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
