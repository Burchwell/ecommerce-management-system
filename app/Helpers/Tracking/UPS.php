<?php

namespace App\Helpers\Tracking;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use JsonException;
use SimpleXMLElement;

/**
 * Class FedEx
 * @package App\Helpers
 */
class UPS
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    public function getTrackingInfo($tracking_number)
    {
        try {
            Log::info("Getting UPS tracking info for: " . $tracking_number);
            $url = 'https://www.ups.com/ups.app/xml/Track';
            $xmlRequest = "<?xml version=\"1.0\"?>
                <AccessRequest xml:lang='en-US'>
                  <AccessLicenseNumber>" . config('services.ups.auth_key') . "</AccessLicenseNumber>
                  <UserId>" . config('services.ups.username') . "</UserId>
                  <Password>" . config('services.ups.username') . "</Password>
                </AccessRequest>
                <?xml version=\"1.0\"?>
                <TrackRequest>
                  <Request>
                    <RequestAction>Track</RequestAction>
                    <RequestOption>activity</RequestOption>
                  </Request>
                  <TrackingNumber>$tracking_number</TrackingNumber>
                </TrackRequest>";

            $request = $this->_client->post('https://www.ups.com/ups.app/xml/Track', [
                'body' => $xmlRequest,
                'headers' => [
                    'Accept' => 'application/xml',
                    'Content-Type' => 'application/xml',
                ]
            ]);

            return json_decode(json_encode(simplexml_load_string($request->getBody()->getContents()), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $e->getMessage();
        }
    }
}
