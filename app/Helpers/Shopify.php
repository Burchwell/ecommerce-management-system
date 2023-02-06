<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;

/**
 * Class Shopify
 * @package App\Helpers
 */
class Shopify
{

    public $shopify;

    public function __construct()
    {
        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'Password' => config('shopify.password')
        ];

        $this->shopify = new ShopifySDK($config);
    }

    public
    function getProducts()
    {
        $response = $this->shopify->Product()->get();
        if ($response->getStatusCode() === 200) {
            try {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error(__CLASS__.$e->getMessage());
            }
        } else {
            return "Error occured.";
        }
    }

    public function getOrders(...$param)
    {
        try {
            $queryString = $this->queryString($param);
            $response = $this->shopify->Order->get($param);
            $response = $this->shopify->get("{$url}?{$queryString}");

            return (json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR))['orders'];
        } catch (\JsonException $e) {
            Log::error(__CLASS__.$e->getMessage());
        }
    }

    private function queryString(...$param) {
        $stringArray = [];
        foreach (array_filter($param) as $key => $value) {
            $stringArray[] = "${$key}=$value";
        }
        return implode(',', $stringArray);
    }
}
