<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | Menggunakan /tmp/views di Vercel (tanpa realpath yang bisa return false)
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        isset($_ENV['VERCEL']) || getenv('VERCEL')
            ? '/tmp/views'
            : storage_path('framework/views')
    ),

];
