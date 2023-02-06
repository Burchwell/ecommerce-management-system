<?php

namespace App\Traits;

use App\Models\Amazon\FbaInboundShipment;
use App\Models\Products\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Str;
use function GuzzleHttp\Psr7\build_query;

/**
 * Class FbaFulfillmentTrait
 * @package App\Traits
 * This trait takes care of requesting an order fulfillment from amazon FBA if we cant ship it ourselves
 */
trait FbaFulfillmentTrait
{
    private $_baseUrl;
    private $_endpoint;
    private $_accessKey;
    private $_sellerId;
    private $_marketPlaceId;
    private $_privateKey;

    private $_awsAccessKeyId;
    private $_mwsAuthToken;

    private $_requiredFields = ['DisplayableOrderComment'];


    public function loadConfig($endpoint = "/FulfillmentOutboundShipment/2010-10-01")
    {

        $this->_baseUrl = config('mws.base_url');
        $this->_endpoint = $endpoint;
        $this->_accessKey = config('mws.access_key');
        $this->_sellerId = config('mws.seller_id');
        $this->_marketPlaceId = config('mws.marketplace_id');
        $this->_privateKey = config('mws.private_key');

        $this->_awsAccessKeyId = config('mws.access_key');
        $this->_mwsAuthToken = config('mws.auth_token');

    }

    /**
     * @param $data
     * @param string $mtd
     * @return mixed|string
     * We need to create a signature, so that amazon can verify our request
     */
    private function signRequestParameters($data, $mtd = "POST")
    {

        $method = $mtd;
        $url = $this->_baseUrl . $this->_endpoint;
        $host = $this->getHost($url);
        $uri = $this->_endpoint;
        $hash = "sha256";

        $params = $this->getStringToSign($method, $host, $uri, $data);
        return $this->sign($params, $this->_privateKey, $hash);
    }


    private function sign($data, $key, $algorithm): string
    {
        $hash = 'sha256';
        if ($algorithm === 'HmacSHA1')
            $hash = 'sha1';
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    private function getStringToSign($method, $host, $path, $params): string
    {
        $parameters = $this->getSortedParameterString($params);

        return "{$method}\n{$host}\n{$path}\n{$parameters}";
    }

    private function getSortedParameterString($parameters): string
    {

        uksort($parameters, 'strcmp');

        return build_query($parameters);
    }

    private function getHost($url): string
    {
        return parse_url($url, PHP_URL_HOST);
    }

    private function getPath($url): string
    {
        $path = parse_url($url, PHP_URL_PATH);

        return (Str::startsWith($path, "/"))
            ? Str::after($path, "/")
            : $path;
    }

    /**
     * Get the ShipmentContent
     */
    public function getShipmentContent($shipmentId) {
        $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
        $data = [
            'AWSAccessKeyId' => $this->_accessKey,
            'Action' => 'GetTransportContent',
            'SellerId' => $this->_sellerId,
            'MWSAuthToken' => $this->_mwsAuthToken,
            'SignatureVersion' => 2,
            'Timestamp' => Carbon::now()->format('c'),
            'Version' => '2010-10-01',
            'SignatureMethod' => 'HmacSHA256',
            'ShipmentId' => $shipmentId ,

        ];

        $response = $this->sendRequest($data);

        $result = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        return $result;
    }
    /**
     * Update FBA Inbound shipment
     */
    public function updateShipment(FbaInboundShipment $shipment) {
        $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");

        $i = 1;
        $inboundItems =[];
        foreach($shipment->items()->get() as $shipmentItem) {
            $inboundItems['InboundShipmentItems.member.'.$i.'.QuantityShipped'] = $shipmentItem->quantity_shipped;
            $inboundItems['InboundShipmentItems.member.'.$i.'.SellerSKU'] = $shipmentItem->sku;
            $i++;
        }
        $data = [
            'AWSAccessKeyId' => $this->_accessKey,
            'Action' => 'UpdateInboundShipment',
            'SellerId' => $this->_sellerId,
            'MWSAuthToken' => $this->_mwsAuthToken,
            'SignatureVersion' => 2,
            'Timestamp' => Carbon::now()->format('c'),
            'Version' => '2010-10-01',
            'SignatureMethod' => 'HmacSHA256',
            'ShipmentId' => $shipment->shipment_id ,
        ];

        $data+= $inboundItems;

        $response = $this->sendRequest($data);

        $result = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        return $result;
    }

    /**
     * @param $data
     * @return string
     * Send request to amazon mws
     */
    public function sendRequest($data)
    {
        sleep(2);
        $client = new Client();
        try {
            $data['Signature'] = $this->signRequestParameters($data);

            $res = $client->request('POST', $this->_baseUrl . $this->_endpoint . '?' . http_build_query($data));
            return $res->getBody()->getContents();
        } catch (\Exception $e) {
            if ( $e instanceof ClientException && $e->getCode() === 503 ) {
                \Log::warning('MWS request limit reached. Waiting 10 seconds before retrying.');
                sleep(15);
                $this->sendRequest($data);
            } else {
                return $e->getResponse()->getBody()->getContents();
            }
        }
    }

    public function requestReport($type)
    {
        try {
            $this->loadConfig("/Reports/2009-01-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'RequestReport',
                'Merchant' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2009-01-01',
                'SignatureMethod' => 'HmacSHA256'
            ];

            if (!empty($type)) {
                $data['ReportType'] = $type;
            }

            return $this->sendRequest($data);
        } catch (ClientException $e) {
            return response()->json(['error' => json_encode($e, JSON_THROW_ON_ERROR)]);
        }
    }

    public function getReportRequestList($type)
    {
        try {
            $this->loadConfig("/Reports/2009-01-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'GetReportRequestList',
                'Merchant' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2009-01-01',
                'SignatureMethod' => 'HmacSHA256'
            ];

            if (!empty($type)) {
                $data['ReportTypeList.Type.1'] = $type;
            }

            return $this->sendRequest($data);
        } catch (ClientException $e) {
            return response()->json(['error' => json_encode($e, JSON_THROW_ON_ERROR)]);
        }
    }

    public function getReportById($id)
    {
        try {
            $this->loadConfig("/Reports/2009-01-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'GetReport',
                'Merchant' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2009-01-01',
                'SignatureMethod' => 'HmacSHA256',
                'ReportId' => $id,
            ];
            return $this->sendRequest($data);
        } catch (ClientException $e) {
            return response()->json(['error' => json_encode($e, JSON_THROW_ON_ERROR)]);
        }
    }

    public function getFeesEstimate(Product $product) {
        try {
            $this->loadConfig("/Products/2011-10-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'GetMyFeesEstimate',
                'SellerId' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2011-10-01',
                'SignatureMethod' => 'HmacSHA256',
                'FeesEstimateRequestList.FeesEstimateRequest.1.MarketplaceId' => $this->_marketPlaceId,
                'FeesEstimateRequestList.FeesEstimateRequest.1.IdType' => 'ASIN',
                'FeesEstimateRequestList.FeesEstimateRequest.1.IdValue' => $product->asin,
                'FeesEstimateRequestList.FeesEstimateRequest.1.IsAmazonFulfilled' => true,
                'FeesEstimateRequestList.FeesEstimateRequest.1.Identifier' => 'request1',
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.ListingPrice.Amount' => $product->price,
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.ListingPrice.CurrencyCode' => 'USD',
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.Shipping.Amount' => 0,
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.Shipping.CurrencyCode' => 'USD',
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.Points.PointsNumber' => 0,
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.Points.PointsMonetaryValue.Amount' => 0,
                'FeesEstimateRequestList.FeesEstimateRequest.1.PriceToEstimateFees.Points.PointsMonetaryValue.CurrencyCode' => 'USD'
            ];
            $response = $this->sendRequest($data);

            if (!is_array($response)) {
                $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

                if (isset($results['GetMyFeesEstimateResult']['FeesEstimateResultList']['FeesEstimateResult']['FeesEstimate'])) {
                    return $results['GetMyFeesEstimateResult']['FeesEstimateResultList']['FeesEstimateResult']['FeesEstimate'];
                } else {
                    return false;
                }
            }
        } catch (ClientException $e) {
            return response()->json(['error' => json_encode($e, JSON_THROW_ON_ERROR)]);
        }
    }

}
