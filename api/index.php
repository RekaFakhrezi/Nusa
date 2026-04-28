<?php

// Pastikan folder storage sementara ada di Vercel
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath, 0777, true);
    @mkdir($storagePath . '/framework/views', 0777, true);
    @mkdir($storagePath . '/framework/cache', 0777, true);
    @mkdir($storagePath . '/framework/sessions', 0777, true);
}

// Paksa Laravel menggunakan folder /tmp
putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "$storagePath/framework/views";

require __DIR__ . '/../public/index.php';
