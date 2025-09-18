<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_KEY'),
    ],
    'paymob' => [
        'base_url'     => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
        'api_key'      => env('PAYMOB_API_KEY'),
        'secret_key'   => env('PAYMOB_SECRET_KEY'),
        'hmac'         => env('PAYMOB_HMAC_SECRET'),
        'iframe_id'    => env('PAYMOB_IFRAME_ID'),
        'integrations' => [
            'card'   => env('PAYMOB_CARD_INTEGRATION_ID'),
            'wallet' => env('PAYMOB_WALLET_INTEGRATION_ID', ''),
            'kiosk'  => env('PAYMOB_KIOSK_INTEGRATION_ID', ''),
        ],
    ],
    'pusher' => [
        'app_id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_APP_CLUSTER', 'eu'),
        'use_tls' => env('PUSHER_USE_TLS', true),
    ],
];
