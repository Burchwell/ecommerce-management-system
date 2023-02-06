<?php

namespace App\Helpers\Tracking;

use App\Helpers\Helpers;
use App\Traits\Tracking;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

/**
 * Class FedEx
 * @package App\Helpers
 */
class FedEx
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    public function _getFedexUpdates($trackingNumber) {
        $fedex = new FedEx();
        $tracking = $fedex->getTrackingInfo($trackingNumber);
        return $this->processFedexTrackingInfo($tracking);
    }

    public function processFedexTrackingInfo($tracking) {
        if (isset($tracking['CompletedTrackDetails']['TrackDetails']['CarrierCode'])) {
            $trackingInfo = [
                'shipment_tracking_number' => $tracking['CompletedTrackDetails']['TrackDetails']['TrackingNumber'],
                'shipment_carrier' => ucwords(Tracking::$carrier_names[Tracking::$carrier_names[$tracking['CompletedTrackDetails']['TrackDetails']['CarrierCode']]]),
                'shipment_code' => ucwords(Tracking::$service_codes[$tracking['CompletedTrackDetails']['TrackDetails']['Service']['ShortDescription']]),
                'shipment_package_type' => ucwords(Tracking::$package_types[$tracking['CompletedTrackDetails']['TrackDetails']['Packaging']])
            ];

            if (isset($tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'])) {
                if (Helpers::is_assoc($tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'])) {
                    $trackingInfo['shipment_special_services'] = $tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings']['Description'];
                } else {
                    $trackingInfo['shipment_special_services'] = $tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'][0]['Description'];
                }
            }

            // TODO: Create Table for Fulfillments (multiple spipments possible)
            if (isset($tracking['CompletedTrackDetails']['TrackDetails']['Events'])) {
                if (isset($tracking['CompletedTrackDetails']['TrackDetails']['Events'][0])) {
                    $trackingInfo['shipment_tracking_last_event'] = $tracking['CompletedTrackDetails']['TrackDetails']['Events'][0]['EventDescription'];
                    $trackingInfo['shipment_tracking_last_updated_api_at'] = Carbon::parse($tracking['CompletedTrackDetails']['TrackDetails']['Events'][0]['Timestamp'])->format('Y-m-d H:i:s');
                }
            }

            return $trackingInfo;
        }
    }

    public function getTrackingInfo($tracking_number)
    {
        $xmlRequest = $this->_prepareXmlRequest($tracking_number);
        Log::info("Getting fedex tracking info for: " . $tracking_number);
        $request = $this->_client->post('https://ws.fedex.com:443/web-services', [
            'body' => $xmlRequest,
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml',
            ]
        ]);
        $response = $request->getBody()->getContents();
        $xml_response = str_replace(['<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/><SOAP-ENV:Body>', '</SOAP-ENV:Body></SOAP-ENV:Envelope>'], '', $response);
        return json_decode(json_encode(simplexml_load_string($xml_response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }

    private function _prepareXmlRequest($tracking_number)
    {
        $auth_key = config('services.fedex.auth_key');
        $password = config('services.fedex.password');
        $account = config('services.fedex.account_number');
        $meter = config('services.fedex.meter_number');
        $xml = "<q0:TrackRequest><q0:WebAuthenticationDetail><q0:UserCredential><q0:Key>$auth_key</q0:Key><q0:Password>$password</q0:Password></q0:UserCredential></q0:WebAuthenticationDetail><q0:ClientDetail><q0:AccountNumber>$account</q0:AccountNumber><q0:MeterNumber>$meter</q0:MeterNumber></q0:ClientDetail><q0:TransactionDetail><q0:CustomerTransactionId>Track Example</q0:CustomerTransactionId></q0:TransactionDetail><q0:Version><q0:ServiceId>trck</q0:ServiceId><q0:Major>16</q0:Major><q0:Intermediate>0</q0:Intermediate><q0:Minor>0</q0:Minor></q0:Version><q0:SelectionDetails><q0:PackageIdentifier><q0:Type>TRACKING_NUMBER_OR_DOORTAG</q0:Type><q0:Value>$tracking_number</q0:Value></q0:PackageIdentifier></q0:SelectionDetails><q0:ProcessingOptions>INCLUDE_DETAILED_SCANS</q0:ProcessingOptions></q0:TrackRequest>";
        $soapHeader = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:q0="http://fedex.com/ws/track/v16"><SOAP-ENV:Body>';
        $soapFooter = '</SOAP-ENV:Body></SOAP-ENV:Envelope>';
        return $soapHeader . $xml . $soapFooter; // Full SOAP Request
    }
}
