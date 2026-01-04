<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' checkout.razorpay.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' api.razorpay.com;",
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Security
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'max_amount' => 1000000, // Maximum payment amount in INR
        'min_amount' => 1, // Minimum payment amount in INR
        'allowed_currencies' => ['INR', 'USD'],
        'rate_limit' => [
            'attempts' => 5,
            'decay_minutes' => 5
        ],
        'webhook_ips' => [
            // Razorpay webhook IPs - update with actual IPs
            '127.0.0.1', // localhost for testing
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    */
    'encryption' => [
        'payment_data' => true,
        'personal_data' => true,
    ],
];