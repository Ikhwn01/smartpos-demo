<?php

// Ensure /tmp has a writable copy of the SQLite database
$tmpDb = '/tmp/database.sqlite';
$srcDb = __DIR__ . '/../database/database.sqlite';

if (!file_exists($tmpDb) && file_exists($srcDb)) {
    @copy($srcDb, $tmpDb);
}

// Override environment variables for Vercel read-only filesystem
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $tmpDb;
$_ENV['LOG_CHANNEL'] = 'errorlog';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

putenv('DB_CONNECTION=sqlite');
putenv("DB_DATABASE={$tmpDb}");
putenv('LOG_CHANNEL=errorlog');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
putenv('VIEW_COMPILED_PATH=/tmp');

// Forward request to Laravel entry point
require __DIR__ . '/../public/index.php';
