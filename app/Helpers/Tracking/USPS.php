<?php

namespace App\Helpers\Tracking;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

/**
 * Class FedEx
 * @package App\Helpers
 */
class USPS
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    public function getTrackingInfo($tracking_number)
    {
        $username = config('services.usps.auth_key');
        Log::info("Getting USPS tracking info for: " . $tracking_number);
        $input_xml = "<TrackFieldRequest USERID=\"$username\"><TrackID ID=\"$tracking_number\"></TrackID></TrackFieldRequest>";
        $url = 'http://production.shippingapis.com/ShippingApi.dll?API=TrackV2&XML='.$input_xml;

        $request = $this->_client->get($url);
        $response = simplexml_load_string($request->getBody()->getContents());
        return json_decode(json_encode($response, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }
}
