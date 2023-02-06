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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_SES_ACCESS_KEY_ID'),
        'secret' => env('AWS_SES_SECRET_ACCESS_KEY'),
        'region' => env('AWS_SES_DEFAULT_REGION', 'us-east-1'),
    ],
    'shipstation' => [
        'api_key' => env('SHIPSTATION_API_KEY', ''),
        'api_secret' => env('SHIPSTATION_API_SECRET', ''),
        'api_url' => env('SHIPSTATION_API_URL', 'ssapi.shipstation.com')
    ],
    'fedex' => [
        'auth_key' => env('FEDEX_AUTH_KEY'),
        'password' => env('FEDEX_AUTH_PASSWORD'),
        'account_number' => env('FEDEX_AUTH_ACCOUNT_NUMBER'),
        'meter_number' => env('FEDEX_AUTH_METER_NUMBER'),
    ],
    'usps' => [
        'auth_key' => env('USPS_AUTH_USERNAME'),
        'auth_password' => env('USPS_AUTH_PASSWORD'),
    ],
    'ups' => [
        'username' => env('UPS_USERNAME'),
        'password' => env('UPS_PASSWORD'),
        'auth_key' => env('UPS_ACCESS_KEY')
    ],
    'ipstack' => [
        'auth_key' => env('IPSTACK_AUTH_KEY')
    ],
    'tools' => [
        'csv_feed' => env('TOOLS_CSV_FEED')
    ],
    'channable' => [
        'csv_feed' => env('CHANNABLE_INVENTORY_URL')
    ]
];
