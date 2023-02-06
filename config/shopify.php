<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Base
    |--------------------------------------------------------------------------
    |
    | Configure API version in which you wish to use in your app(s). Defaults to
    | 2019-10 for supporting the new cursor based navigation.
    |
    | e.g. admin, admin/api/2020-07, ...
    |
    | @see https://help.shopify.com/en/api/versioning
    */

    'api_base' => env('SHOPIFY_API_BASE', 'admin/api/2020-07'),

    /*
    |--------------------------------------------------------------------------
    | Shopify Shop
    |--------------------------------------------------------------------------
    |
    | If your app is managing a single store, you should configure it here.
    |
    | e.g. my-cool-store.myshopify.com
    */

    'store' => env('SHOPIFY_STORE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Api Key
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'api_key' => env('SHOPIFY_AUTH_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Api Password
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'api_secret_key' => env('SHOPIFY_AUTH_API_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Api Password
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'password' => env('SHOPIFY_AUTH_API_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Shared Secret
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'shared_secret' => env('SHOPIFY_AUTH_SHARED_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Token
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'token' => env('SHOPIFY_AUTH_ACCESS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Api Scopes
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'scopes' => env('SHOPIFY_API_SCOPES', [
        'read_products',
        'read_script_tags',
        'read_content',
        'read_themes',
        'read_products',
        'read_product_listings',
        'read_customers',
        'read_orders',
        'read_draft_orders',
        'read_inventory',
        'read_locations',
        'read_script_tags',
        'read_fulfillments',
        'read_assigned_fulfillment_orders',
        'read_merchant_managed_fulfillment_orders',
        'read_third_party_fulfillment_orders',
        'read_shipping',
        'read_analytics',
        'read_checkouts',
        'read_reports',
        'read_price_rules',
        'read_discounts',
        'read_marketing_events',
        'read_resource_feedbacks',
        'read_shopify_payments_payouts',
        'read_shopify_payments_disputes',
        'read_translations',
        'read_locales'
    ]),
];
