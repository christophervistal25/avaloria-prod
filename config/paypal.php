<?php

/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'live'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', 'AQqPFLFNQCPvdqLu0NRj3ygV2TuTzx_k1_AYgC3f78gKo1PzoFY9Dlwyzz4DMNbaCvUYDxUb8W4lYbs5'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', 'EBSVSD5z9E436wDwRWPvaG-WBKGeAaDEd6FcEZf646vSl9AJZvcBTbcpXaBhfDT-8CYbrEFzH2Nz_d5y'),
        'app_id' => 'APP-80W284485P519543T',
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID', 'AQ1VJ3kB1ghSyVCAEWFkoYXdZHmjYQQyW3rMzGE3o3NW2HO8aDkndIF40YmuXQ9ngBy4mvNtGc3JeOp9'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', 'ENb1HpHDk3g0W1ZZVDR4XQeBfJiROk-HdD5smpwu4HnV9dY1uTLEPp073eCFs0DlhiHXwgGbgM54cl8D'),
        'app_id' => env('PAYPAL_LIVE_APP_ID', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Authorization'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency' => env('PAYPAL_CURRENCY', 'EUR'),
    'notify_url' => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale' => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
