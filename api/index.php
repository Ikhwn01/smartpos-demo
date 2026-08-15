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

    // Ensure storage structure exists in /tmp
    @mkdir('/tmp/logs', 0777, true);
    @mkdir('/tmp/framework/views', 0777, true);
    @mkdir('/tmp/framework/sessions', 0777, true);
    @mkdir('/tmp/framework/cache', 0777, true);

    $appKey = 'base64:HZhD1Z2XLzBOIJjzrAm3Qr76NVyvQqRwo3qj5gyhaRo=';

    // Override environment variables for Vercel read-only filesystem
    $_ENV['APP_KEY'] = $appKey;
    $_ENV['APP_ENV'] = 'production';
    $_ENV['APP_DEBUG'] = 'false';
    $_ENV['LOG_CHANNEL'] = 'errorlog';
    $_ENV['LOG_PATH'] = $tmpLog;
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $tmpDb;
    $_ENV['SESSION_DRIVER'] = 'database';
    $_ENV['SESSION_COOKIE'] = 'pos_sess';
    $_ENV['SESSION_LIFETIME'] = '120';
    $_ENV['SESSION_EXPIRE_ON_CLOSE'] = 'false';
    $_ENV['CACHE_STORE'] = 'array';
    $_ENV['VIEW_COMPILED_PATH'] = '/tmp/framework/views';

    putenv("APP_KEY={$appKey}");
    putenv('APP_ENV=production');
    putenv('APP_DEBUG=false');
    putenv('LOG_CHANNEL=errorlog');
    putenv("LOG_PATH={$tmpLog}");
    putenv('DB_CONNECTION=sqlite');
    putenv("DB_DATABASE={$tmpDb}");
    putenv('SESSION_DRIVER=database');
    putenv('SESSION_COOKIE=pos_sess');
    putenv('SESSION_LIFETIME=120');
    putenv('SESSION_EXPIRE_ON_CLOSE=false');
    putenv('CACHE_STORE=array');
    putenv('VIEW_COMPILED_PATH=/tmp/framework/views');

    // Forward request to Laravel entry point
    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Vercel Server Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
