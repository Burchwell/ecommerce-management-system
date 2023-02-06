<?php

namespace App\Models\Amazon;

use Carbon\Carbon;
use Exception;
use PHPShopify\Exception\CurlException;

class Mws
{

    public function amazonRequest($uri, $version, $action, $param = null)
    {
        try {
            $params = [
                    'AWSAccessKeyId' => config('mws.access_key'),
                    'Action' => $action,
                    'SellerId' => config('mws.seller_id'),
                    'SignatureMethod' => "HmacSHA256",
                    'SignatureVersion' => "2",
                    'Timestamp' => Carbon::now()->format('c'),
                    'Version' => $version
                ] + $param;


            // Sort the URL parameters
            $url_parts = array();
            foreach (array_keys($params) as $key)
                $url_parts[] = $key . "=" . str_replace('%7E', '~', rawurlencode($params[$key]));
            sort($url_parts);

            // Construct the string to sign
            $url_string = str_replace("%7E", "~", implode("&", $url_parts));
            $string_to_sign = "POST\n" . env('FBA_FULFILLMENT_BASE_URL') . "\n{$uri}\n" . $url_string;

            // Sign the request
            $signature = hash_hmac('sha256', $string_to_sign, env('FBA_FULFILLMENT_PRIVATE_KEY'), TRUE);

            // Base64 encode the signature and make it URL safe
            $signature = rawurlencode(base64_encode($signature));

            $url = env('FBA_FULFILLMENT_BASE_URL') . "/" . $uri . '?' . $url_string . "&Signature=" . $signature;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $response = curl_exec($ch);
        } catch (CurlException $e) {
            dd($e);
        }
    }
}
