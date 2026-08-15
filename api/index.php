<?php

try {
    // Auto-truncate bloated HTTP cookie header from client browser to prevent Vercel 494 header overflow (only if extremely large)
    if (isset($_SERVER['HTTP_COOKIE']) && strlen($_SERVER['HTTP_COOKIE']) > 3500) {
        $_SERVER['HTTP_COOKIE'] = '';
    }
    if (isset($_ENV['HTTP_COOKIE']) && strlen($_ENV['HTTP_COOKIE']) > 3500) {
        $_ENV['HTTP_COOKIE'] = '';
    }

    // Flag Vercel environment
    putenv('VERCEL=1');
    $_ENV['VERCEL'] = '1';
    $_SERVER['VERCEL'] = '1';

    // Populate $_COOKIE array from HTTP_COOKIE header for Vercel CLI/Serverless PHP runtime
    $rawCookies = $_SERVER['HTTP_COOKIE'] ?? $_SERVER['COOKIE'] ?? $_ENV['HTTP_COOKIE'] ?? '';
    if (!empty($rawCookies)) {
        $cookiePairs = explode(';', $rawCookies);
        foreach ($cookiePairs as $pair) {
            $pair = trim($pair);
            if (!empty($pair) && strpos($pair, '=') !== false) {
                list($key, $val) = explode('=', $pair, 2);
                $_COOKIE[trim($key)] = urldecode(trim($val));
            }
        }
    }

    // Ensure /tmp has a writable copy of the SQLite database and log file
    $tmpDb = '/tmp/database.sqlite';
    $tmpLog = '/tmp/laravel.log';
    $srcDb = __DIR__ . '/../database/database.sqlite';

    if (!file_exists($tmpDb) || filesize($tmpDb) === 0) {
        if (file_exists($srcDb) && filesize($srcDb) > 0) {
            @copy($srcDb, $tmpDb);
        } else {
            @touch($tmpDb);
        }
    }

    if (!file_exists($tmpLog)) {
        @touch($tmpLog);
    }

    // Unlink stale config/route cache files in /tmp
    @unlink('/tmp/config.php');
    @unlink('/tmp/routes.php');
    @unlink('/tmp/events.php');
    @unlink('/tmp/services.php');

    // Ensure storage structure exists in /tmp
    @mkdir('/tmp/logs', 0777, true);
    @mkdir('/tmp/framework/views', 0777, true);
    @mkdir('/tmp/framework/sessions', 0777, true);
    @mkdir('/tmp/framework/cache', 0777, true);

    $appKey = 'base64:HZhD1Z2XLzBOIJjzrAm3Qr76NVyvQqRwo3qj5gyhaRo=';

    // Bootstrap Laravel and override config directly to guarantee session persistence
    if (!defined('LARAVEL_START')) {
        define('LARAVEL_START', microtime(true));
    }
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var \Illuminate\Foundation\Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';

    config([
        'app.key' => $appKey,
        'app.env' => 'production',
        'app.debug' => false,
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => $tmpDb,
        'session.driver' => 'file',
        'session.lifetime' => 120,
        'session.expire_on_close' => false,
        'session.cookie' => 'pos_sess',
        'session.files' => '/tmp/framework/sessions',
        'cache.default' => 'array',
        'view.compiled' => '/tmp/framework/views',
    ]);

    $app->handleRequest(\Illuminate\Http\Request::capture());

} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Vercel Server Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
