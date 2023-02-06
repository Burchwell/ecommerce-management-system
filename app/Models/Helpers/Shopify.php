<?php
namespace app\Helpers;

use PHPShopify\ShopifySDK;

class Shopify {
    protected $shopify;

    public function __construct($config = null)
    {
        $config = $config ?: [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'SharedSecret' => config('shopify.shared_secret')
        ];

        $this->shopify = new ShopifySDK($config);
    }
}
