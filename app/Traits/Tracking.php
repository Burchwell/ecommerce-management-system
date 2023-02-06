<?php
namespace App\Traits;

use App\Helpers\Helpers;
use App\Helpers\Tracking\FedEx;
use App\Helpers\Tracking\UPS;
use App\Helpers\Tracking\USPS;
use App\Models\Order;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use Log;

/**
 * Trait Tracking
 * @package App\Traits
 */
trait Tracking
{
    private $_client;

    public static $carrier_names = [
        '' => 'N/A',
        'fdxe' => "FedEx",
        'fhd' => "FedEx",
        'fdxg' => "FedEx",
        'fedex' => "FedEx",
        'ups' => "UPS",
        'UPS' => "UPS",
        'stamps_com' => "USPS",
        'usps' => "USPS",
    ];

    public static $service_codes = [
        '' => 'N/A',
        'hd' => 'Home Delivery',
        'home delivery' => 'Home Delivery',
        'ground' => 'Ground',
        'fg' => 'Ground',
        'p-2' => '2-Day Express',
        '2-day express' => '2-Day Express',
        '2day' => '2-Day Express',
        'ig' => 'International Ground',
        'priority mail' => 'Priority Mail',
        '2nd day air' => '2nd Day Air',
	    'next day air saver' => 'Next Day Air',
        'first class mail' => 'First Class Mail',
        '3 day select' => '3 Day Select',
        'next day air' => 'Next Day Air'
    ];

    public static $package_types = [
        '' => 'N/A',
        'package' => 'Package',
        'fedex pak' => 'Fedex Pak',
        'fedex pak onerate' => 'Fedex Pak',
        'fedex envelope' => 'Fedex Envelope',
        'fedex envelope onerate' => 'Fedex Envelope',
	    'flat rate legal envelope' => 'FedEx Envelope',
        'your packaging' => 'Package',
    ];

    public static $special_services = [
        'direct signature' => 'Signature Required',
        'Signature Required' => 'Signature Required',
        'home delivery' => null
    ];

    /**
     * Tracking constructor.
     */
    public function __construct()
    {
        $this->_client = new Client();
    }

    /**
     * @param $shipment
     * @return array
     */
    private function _cleanUpTrackingInfo($shipment)
    {
        $carrier = $this->_getCarrierByTrackingNumber($shipment['trackingCode']);
        return [
            'shipstation_id' => $shipment['orderId'],
            'shipment_tracking_number' => $shipment['trackingCode'],
            'shipment_carrier' => self::$carrier_names[strtolower($carrier)],
            'shipment_code' => self::$service_codes[strtolower(str_replace('_', " ", str_replace(['amazon_', strtolower($carrier) . "_"], "", $shipment['serviceCode'])))],
            'shipment_package_type' => self::$package_types[strtolower(str_replace('_', " ", $shipment['packageCode']))] ?? null,
            'shipment_special_services' => self::$special_services[strtolower(str_replace('_', " ", $shipment['shipmentConfirmation']))] ?? null
        ];
    }

    /**
     * @param $carrier
     * @param $trackingNumber
     * @return array|null
     * @throws JsonException
     */
    private function _getTrackingUpdates($trackingNumber)
    {
        $carrier = strtolower($this->_getCarrierByTrackingNumber($trackingNumber));
        switch ($carrier) {
            case 'fedex':
                return $this->_getFedexUpdates($trackingNumber);
            case 'usps':
                return $this->_getUSPSUpdates($trackingNumber);
            case 'ups':
                return $this->_getUPSUpdates($trackingNumber);
            default:
                Log::info("Tracking not available for Carrier $carrier...");
                return null;
        }
    }

    public function _getFedexUpdates($trackingNumber)
    {
        $fedex = new FedEx();
        $tracking = $fedex->getTrackingInfo($trackingNumber);
        return $this->processFedexTrackingInfo($tracking);
    }

    public function processFedexTrackingInfo($tracking)
    {
        if (isset($tracking['CompletedTrackDetails']['TrackDetails']['CarrierCode'])) {
            $trackingInfo = [
                'shipment_tracking_number' => $tracking['CompletedTrackDetails']['TrackDetails']['TrackingNumber'],
                'shipment_carrier' => ucwords(self::$carrier_names[strtolower($tracking['CompletedTrackDetails']['TrackDetails']['CarrierCode'])]),
                'shipment_code' => ucwords(self::$service_codes[strtolower($tracking['CompletedTrackDetails']['TrackDetails']['Service']['ShortDescription'])]),
                'shipment_package_type' => ucwords(self::$package_types[strtolower($tracking['CompletedTrackDetails']['TrackDetails']['Packaging'])])
            ];

            if (isset($tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'])) {
                if (Helpers::is_assoc($tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'])) {
                    $trackingInfo['shipment_special_services'] = self::$special_services[$tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings']['Description']] ?? null;
                } else {
                    $trackingInfo['shipment_special_services'] = self::$special_services[$tracking['CompletedTrackDetails']['TrackDetails']['SpecialHandlings'][0]['Description']] ?? null;
                }
            }

            // TODO: Create Table for Fulfillments (multiple spipments possible)
            if (isset($tracking['CompletedTrackDetails']['TrackDetails']['Events'])) {
                if (Helpers::is_assoc($tracking['CompletedTrackDetails']['TrackDetails']['Events'])) {
                    $trackingInfo['shipment_tracking_last_event'] = $tracking['CompletedTrackDetails']['TrackDetails']['Events']['EventDescription'];
                    $trackingInfo['shipment_tracking_last_updated_api_at'] = $tracking['CompletedTrackDetails']['TrackDetails']['Events']['Timestamp'];
                    $trackingInfo['status'] = 'shipped';
                } else {
                    $trackingInfo['shipment_tracking_last_event'] = $tracking['CompletedTrackDetails']['TrackDetails']['Events'][0]['EventDescription'];
                    $trackingInfo['shipment_tracking_last_updated_api_at'] = Carbon::parse($tracking['CompletedTrackDetails']['TrackDetails']['Events'][0]['Timestamp'])->format('Y-m-d H:i:s');
                    $trackingInfo['status'] = 'shipped';
                }
            }

            return $trackingInfo;
        }
    }

    private function _getUSPSUpdates($trackingNumber)
    {
        $usps = new USPS();
        $tracking = $usps->getTrackingInfo($trackingNumber);

        $trackingInfo = [
            'shipment_tracking_number' => $trackingNumber,
            'shipment_carrier' => 'USPS'
        ];

        if (isset($tracking['TrackInfo']['Error']) && $tracking['TrackInfo']['Error']['Number'] === '-2147219283') {
            $trackingInfo['shipment_tracking_last_event'] = explode('.', $tracking['TrackInfo']['Error']['Description'])[0];
            $trackingInfo['shipment_tracking_last_updated_api_at'] = now();
        } else {
            if (isset($tracking['TrackInfo']['TrackSummary'])) {
                $dateTime = $tracking['TrackInfo']['TrackSummary']['EventDate'] . " ";
                $dateTime .= !empty($tracking['TrackInfo']['TrackSummary']['EventTime']) ? $tracking['TrackInfo']['TrackSummary']['EventTime'] : '00:00:00';

                $trackingInfo['shipment_tracking_last_event'] = $tracking['TrackInfo']['TrackSummary']['Event'];
                $trackingInfo['shipment_tracking_last_updated_api_at'] = Carbon::parse($dateTime)->format('c');
                $trackingInfo['status'] = 'shipped';
            }

            // TODO: Create Table for Fulfillments (multiple spipments possible)

            return $trackingInfo;
        }
    }

    private function _getUPSUpdates($trackingNumber)
    {
        $ups = new UPS();
        $tracking = $ups->getTrackingInfo($trackingNumber);

        $trackingInfo = [
            'shipment_tracking_number' => $trackingNumber,
            'shipment_carrier' => 'UPS'
        ];

        if (isset($tracking['Shipment']['Package']['Activity'][0]['Status'])) {
            $date = $tracking['Shipment']['Package']['Activity'][0]['GMTDate'];
            $time = $tracking['Shipment']['Package']['Activity'][0]['GMTTime'];
            $offset = $tracking['Shipment']['Package']['Activity'][0]['GMTOffset'];

            $trackingInfo['shipment_tracking_last_event'] = $tracking['Shipment']['Package']['Activity'][0]['Status']['StatusType']['Description'];
            $trackingInfo['shipment_tracking_last_updated_api_at'] = Carbon::parse("{$date} {$time}{$offset}")->format('c');
            $trackingInfo['status'] = 'shipped';
        } else if ($tracking['Shipment']['Package']['Activity']['Status']) {
            $date = $tracking['Shipment']['Package']['Activity']['GMTDate'];
            $time = $tracking['Shipment']['Package']['Activity']['GMTTime'];
            $offset = $tracking['Shipment']['Package']['Activity']['GMTOffset'];

            $trackingInfo['shipment_tracking_last_event'] = $tracking['Shipment']['Package']['Activity']['Status']['StatusType']['Description'];
            $trackingInfo['shipment_tracking_last_updated_api_at'] = Carbon::parse("{$date} {$time}{$offset}")->format('c');
            $trackingInfo['status'] = 'shipped';
        }

        // TODO: Create Table for Fulfillments (multiple spipments possible)
        return $trackingInfo;
    }

    /**
     * @param $trackingNumber
     * @return false|string
     */
    private function _getCarrierByTrackingNumber($trackingNumber)
    {
        switch (true) {
            case preg_match("/(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)/", $trackingNumber):
            case preg_match("/^E\D{1}\d{9}\D{2}$|^9\d{15,21}$/", $trackingNumber):
            case preg_match("/^91[0-9]+$/", $trackingNumber):
            case preg_match("/^[A-Za-z]{2}[0-9]+US$/", $trackingNumber):
                Log::info("Carrier identified as USPS");
                return "USPS";
            case preg_match("/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/", $trackingNumber):
            case preg_match("/\b((98\d\d\d\d\d?\d\d\d\d|98\d\d) ?\d\d\d\d ?\d\d\d\d( ?\d\d\d)?)\b/", $trackingNumber):
            case preg_match("/^[0-9]{15}$/", $trackingNumber):
                Log::info("Carrier identified as FedEx");
                return "FedEx";
            case preg_match("/\b(1Z ?[0-9A-Z]{3} ?[0-9A-Z]{3} ?[0-9A-Z]{2} ?[0-9A-Z]{4} ?[0-9A-Z]{3} ?[0-9A-Z]|[\dT]\d\d\d ?\d\d\d\d ?\d\d\d)\b/", $trackingNumber):
                Log::info("Carrier identified as UPS");
                return "UPS";
            default:
                return false;
        }
    }

    /**
     * @param $order_number
     * @return Order|array|Model
     * @throws GuzzleException
     */
    private function _getNovaData($order_number, $order = null)
    {
        try {
            $client = new Client();
            $request = $client->request('GET', "https://nova.skaraudio.com/api/order/{$order_number}?api_token=Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK");
            $response = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            $new = [
                'order_number' => $response['orderNumber'],
                'source' => $order->source ?? 'Nova',
                'shipstation_id' => $response['orderId'],
                'city' => $response['billingCity'],
                'state' => $response['billingState'],
                'zipcode' => $response['billingPostalCode'],
                'status' => $order->status ?? $response['orderStatus'],
            ];

            if ($response['orderNumber'] !== $response['orderKey']) {
                $new['order_id'] = $response['orderKey'];
            }


            if (!empty($response['tracking_info'])) {
                $special_service = ucwords(str_replace('_', " ", $response['tracking_info'][0]['shipmentConfirmation']));
                $special_service = (stripos($special_service, 'Signature') !== false) ? 'Signature Required' : $special_service;
                $special_service = (stripos($special_service, 'Delivery') !== false) ? null : $special_service;

                if (isset($response['tracking_info'][0]['shipDate'])) {
                    $new['shipped_at'] = $response['tracking_info'][0]['shipDate'];
                    $new['status'] = 'shipped';
                }

                if ($response['tracking_info'][0]['trackingCode']) {
                    $new['shipment_carrier'] = $this->_getCarrierByTrackingNumber($response['tracking_info'][0]['trackingCode']);
                    $new['shipment_tracking_number'] = $response['tracking_info'][0]['trackingCode'];
                    $new['shipment_code'] = ucwords(self::$service_codes[str_replace('_', ' ', str_replace(['amazon_', (strtolower($new['shipment_carrier'] . "_"))], "", $response['tracking_info'][0]['serviceCode']))]);
                    $new['shipment_package_type'] = ucwords(self::$package_types[str_replace('_', " ", $response['tracking_info'][0]['packageCode'])]);
                    $new['shipment_special_services'] = ucwords($special_service);
                }
            }

            $tracking_number = $new['shipment_tracking_number'] ?? $order->shipment_tracking_number ?? null;

            if ($tracking_number !== null) {
                $trackingInfo = $this->_getTrackingUpdates($tracking_number);
                if ($trackingInfo !== null) {
                    $new += $trackingInfo;
                }
            }

            $new['shipment_tracking_last_updated_api_at'] = now();
            return Order::updateOrCreate(['order_number' => $order_number], $new);
        } catch (JsonException $e) {
            return $e->getTrace();
        }
    }
}
