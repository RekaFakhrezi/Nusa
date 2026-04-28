<?php

// Set VERCEL env flag untuk deteksi di bootstrap/app.php dan config/view.php
$_ENV['VERCEL'] = '1';
putenv('VERCEL=1');

// Buat folder /tmp yang dibutuhkan Laravel SEBELUM boot
foreach ([
    '/tmp/views',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Tangkap semua error agar bisa melihat error asli
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Stack trace:\n" . $e->getTraceAsString();
}
