<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Satu Sehat API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan API Satu Sehat
    |
    */

    // Environment: sandbox atau production
    'environment' => env('SATUSEHAT_ENV', 'sandbox'),

    // Base URL untuk berbagai environment
    'base_urls' => [
        'sandbox' => 'https://api-satusehat-stg.dto.kemkes.go.id',
        'production' => 'https://api-satusehat.kemkes.go.id',
    ],

    // Credentials untuk OAuth
    'client_id' => env('SATUSEHAT_CLIENT_ID', ''),
    'client_secret' => env('SATUSEHAT_CLIENT_SECRET', ''),

    // Organization ID
    'organization_id' => env('SATUSEHAT_ORGANIZATION_ID', ''),

    // Timeout untuk request (detik)
    'timeout' => env('SATUSEHAT_TIMEOUT', 30),

    // Logging configuration
    'logging' => [
        'enabled' => env('SATUSEHAT_LOGGING_ENABLED', true),
        'channel' => env('SATUSEHAT_LOG_CHANNEL', 'daily'),
        'level' => env('SATUSEHAT_LOG_LEVEL', 'info'),
    ],

    // Token storage
    'token_storage' => [
        'table' => 'satusehat_tokens',
        'cache_minutes' => 60, // Cache token selama 60 menit
    ],

    // Rate limiting
    'rate_limit' => [
        'enabled' => true,
        'max_requests' => 100,
        'per_minutes' => 1,
    ],
];
