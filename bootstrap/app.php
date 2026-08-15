<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'login',
            'demo/*',
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check.installed' => \App\Http\Middleware\CheckInstalledMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\CheckInstalledMiddleware::class,
            \App\Http\Middleware\SetLocaleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e) {
            return response(
                "<h1>Original Application Exception</h1>" .
                "<p><strong>Class:</strong> " . get_class($e) . "</p>" .
                "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>" .
                "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>",
                500
            );
        });
    })->create();

if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || getenv('VERCEL') || defined('VERCEL')) {
    $app->useStoragePath('/tmp');
}

return $app;
