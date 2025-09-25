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
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'api_version' => env('STRIPE_API_VERSION', '2023-10-16'),
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

    'fawry' => [
        'base_url' => env('FAWRY_BASE_URL', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/'),
        'merchant_code' => env('FAWRY_MERCHANT_CODE'),
        'security_key' => env('FAWRY_SECURITY_KEY'),
        'merchant_ref_num' => env('FAWRY_MERCHANT_REF_NUM'),
        'webhook_secret' => env('FAWRY_WEBHOOK_SECRET'),
        'environment' => env('FAWRY_ENVIRONMENT', 'staging'), // staging or production
    ],
    'pusher' => [
        'app_id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_APP_CLUSTER', 'eu'),
        'use_tls' => env('PUSHER_USE_TLS', true),
    ],

    'default_payment_gateway' => env('DEFAULT_PAYMENT_GATEWAY', 'paymob'),
];
