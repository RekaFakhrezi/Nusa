<?php

// Set VERCEL env jika belum ada (untuk deteksi di bootstrap/app.php)
$_ENV['VERCEL'] = '1';

require __DIR__ . '/../public/index.php';
