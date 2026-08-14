<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalledMiddleware
{
    /**
     * Handle installation state check.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $installedFile = storage_path('installed');
        $isInstalled = file_exists($installedFile);
        $isInstallRoute = $request->is('install*');

        if (!$isInstalled && !$isInstallRoute) {
            return redirect()->route('install.step1');
        }

        if ($isInstalled && $isInstallRoute) {
            return redirect()->route('dashboard')->with('info', 'Application is already installed.');
        }

        return $next($request);
    }
}
