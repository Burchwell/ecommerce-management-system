<?php
    return [
        'personal_access_client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID', '1'),
        'personal_access_client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET', ''),

        'access_token_expiry_days' => env('ACCESS_TOKEN_EXPIRY_DAYS', 15),
        'personal_access_token_expiry_days' => env('PERSONAL_ACCESS_TOKEN_EXPIRY_DAYS', 10),
        'personal_refresh_token_expiry_days' => env('PERSONAL_REFRESH_TOKEN_EXPIRY_DAYS', 30)
    ];
