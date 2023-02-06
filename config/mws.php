<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'base_url' => env('FBA_FULFILLMENT_BASE_URL'),
    'access_key' => env('FBA_FULFILLMENT_ACCESS_KEY', ''),
    'private_key' => env('FBA_FULFILLMENT_PRIVATE_KEY', ''),
    'seller_id' => env('FBA_FULFILLMENT_SELLER_ID', ''),
    'marketplace_id' => env('FBA_FULFILLMENT_MARKETPLACE_ID', ''),
    'auth_token' => env('MWS_AUTH_TOKEN', '')
];
