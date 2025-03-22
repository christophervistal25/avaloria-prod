<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Donate Vouchers Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the voucher processing system.
    |
    */

    // IDs of voucher items to track
    'voucher_ids' => [
        '7652', '7653', '7654', '7655',
    ],
    
    // Cache TTL in seconds (default: 1 hour)
    'cache_ttl' => env('DONATE_VOUCHERS_CACHE_TTL', 3600),
    
    // Maximum number of records to process at once
    'chunk_size' => env('DONATE_VOUCHERS_CHUNK_SIZE', 50),
    
    // Safety limit for mail vouchers
    'mail_limit' => env('DONATE_VOUCHERS_MAIL_LIMIT', 10000),
];