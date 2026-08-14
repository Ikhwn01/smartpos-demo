<?php

// Ensure /tmp/database.sqlite and /tmp/installed exist on Vercel cold start
$sqlitePath = '/tmp/database.sqlite';
$installedPath = __DIR__ . '/../storage/installed';

if (!file_exists($sqlitePath) && file_exists(__DIR__ . '/../database/database.sqlite')) {
    @copy(__DIR__ . '/../database/database.sqlite', $sqlitePath);
}

if (!file_exists($installedPath)) {
    @file_put_contents($installedPath, date('Y-m-d H:i:s'));
}

// Forward Vercel serverless request to Laravel entry point
require __DIR__ . '/../public/index.php';
