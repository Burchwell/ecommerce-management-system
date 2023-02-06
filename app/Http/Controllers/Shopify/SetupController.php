<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPShopify\AuthHelper;
use PHPShopify\ShopifySDK;

/**
 * Class SetupController
 * @package App\Http\Controllers\Shopify
 */
class SetupController extends Controller
{

    public function install()
    {
        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'SharedSecret' => config('shopify.api_secret_key')
        ];

        ShopifySDK::config($config);

        $scopes = config('shopify.scopes');
        $redirectUrl = 'https://core.skaraudio.com/shopify/setup/token';

        return AuthHelper::createAuthRequest($scopes, $redirectUrl);
    }

    public function getAccessToken(Request $request)
    {
        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'SharedSecret' => config('shopify.api_secret_key')
        ];

        ShopifySDK::config($config);
        $accessToken = AuthHelper::getAccessToken();

        return response()->json(compact('accessToken'));
    }
}
