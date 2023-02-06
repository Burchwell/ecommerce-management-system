<?php

namespace App\Helpers;

use App\Models\Products\Warehouse;
use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\Self_;

class ShipEngine
{
    public $carriers = [
        "se-251780",
        "se-251778",
        "se-171331"
    ];

    public static
    function getShipmentStatus($carrier, $tracking_number)
    {
        $carrier_codes = [
            "FedEx" => "fedex",
            "UPS" => 'ups',
            "U.S. Mail" => 'usps',
            "USPS" => 'usps',
        ];
        $carrier_code = $carrier_codes[$carrier];
        $client = self::_newShipEngineClient();
        $response = $client->get('https://api.shipengine.com/v1/tracking', ["query" => [
            "tracking_number" => $tracking_number,
            "carrier_code" => $carrier_code
        ]]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public static
    function getEstimates($from, $to, $weight,  $dimensions, $carrier, $service_code) {
        $warehouses = Warehouse::all();
        $json = array_merge(self::carriers, $from, $to, $weight, $dimensions);
        $json["confirmation"] = "none";
        $json["address_residential_indicator"] = "no";
        $client = self::_newShipEngineClient();
        $response = $client->post('https://api.shipengine.com/v1/rates/estimates', ["json"=>$json]);
        if ($response->getStatusCode() === 200) {
            $estimates = $response->getBody()->getContents();
            dd($estimates);
        }
    }

    private static
    function _newShipEngineClient()
    {
        $options = [
            'headers' => [
                'Accept' => 'applictaion/json',
                'Content-Type' => 'applictaion/json',
                'API-Key' => config('shipstation.shipengine.api_key')
            ]
        ];

        return new Client($options);
    }
}
