<?php

namespace App\Helpers;

use App\Models\ChannelAdvisor\AmazonSellerCentral;
use App\Models\ChannelAdvisor\EbayUS;
use App\Models\ChannelAdvisor\Newegg;
use GuzzleHttp\Client;

class ChannelAdvisor
{

    protected $auth;

    public $client;

    public static $site = [
        "Amazon Seller Central - US" => AmazonSellerCentral::class,
        "eBay Fixed Price US" => EbayUS::class,
        "Newegg" => Newegg::class
    ];

    public static $adjustmentReasons = [
        0 => 'No inventory',
        1 => 'Customer Return',
        2 => 'General Adjustment',
        3 => 'High bidder didn\'t send payment',
        4=>'High bidder sent payment but check bounced or payment was stopped',
        5=>'Merchandise Not Received',
        6=>'Buyer Canceled',
        7=>'Item damaged',
        8=>'Seller goodwill',
        9=>'Shipping Address Undeliverable',
        10=>'Customer Exchange',
        12=>'High bidder didn\'t comply with seller\'s terms & conditions stated in listing',
        13=>'Both parties mutually agreed not to complete the transaction',
        14=>'Partial credit - One or more of your dutch auction bidders backed out of the sale',
        15=>'Partial credit - Winning bidder in dutch auction declined to take reduced quantity',
        16=>'Partial credit - Seller agreed to change transaction terms with the buyer',
        17=>'Partial credit - High bidder in Dutch auction did not complete the transaction.',
        100=>'General Adjustment',
        101=>'ItemNotAvailable',
        102=>'CustomerReturnedItem',
        103=>'CouldNotShip',
        104=>'AlternateItemProvided',
        105=>'BuyerCancelled',
        106=>'CustomerExchange',
        107=>'MerchandiseNotReceived',
        108=>'ShippingAddressUndeliverable',
        201=>'Pixmania refund',
        300=>'The buyer wants to return an item that arrived already damaged.',
        301=>'The buyer wants to return an item becasue it arrived late, as defined by the estimated shipping time window.',
        302=>'An eBay Now order was cancelled by the buyer.',
        303=>'An eBay Now order is being cancelled because the buyer was a no-show at the pre-arranged delivery location.',
        304=>'An eBay Now order is being cancelled because the buyer did not schedule a delivery of the item.',
        305=>'An eBay Now order is being cancelled because the buyer refused to receive the item from an eBay Now valet.',
        306=>'The buyer wants to return an item found to be defective.',
        307=>'The item is being returned because the buyer feels that the actual item is a lot different than the item portrayed in the listing.',
        308=>'The buyer wants to return a food (or other) item that has surpassed its expiration date.',
        309=>'The buyer wants to return an item that may possibly be inauthentic or counterfeit.',
        310=>'The item is being returned because the buyer found a better price for the item elsewhere.',
        311=>'An In-Store Pickup item was returned to the store. This value only applies to In-Store Pickup orders',
        312=>'The item is being returned because the buyer discovered that the item was missing one or more parts.',
        313=>'Tthe item is being returned because the buyer no longer wants the item.',
        314=>'The buyer wants to return the item for no particular reason.',
        315=>'The item is being returned because the buyer received an item that was not as described in the item\'s description.',
        316=>' This enumeration value indicates that the item is being returned because the buyer accidentally purchased the item.',
        317=>' The item is being returned because the buyer received an item that was the wrong item completely.',
        318=>' The item is being returned because the buyer purchased the wrong item.',
        319=>' This enumeration value indicates that the return reason was not classified,or is unknown.',
        320=>' An eBay Now order is being cancelled because the item was out of stock.',
        321=>' The buyer wants to return an item that was a gift.',
        322=>' An eBay Now order was cancelled because the eBay Now valet was unable to deliver the item to the buyer.',
        323=>' An eBay Now order was cancelled because no eBay Now valets were availabe to deliver the item to the buyer.',
        324=>' The buyer wants to return a clothing(or other)item found to be the wrong size.',
        400=>' Buyer found a better price ',
        401=>' Buyer changed their mind ',
        402=>' Item was damaged or defective ',
        403=>' Item did not match the description ',
        404=>' Item does not fit ',
        405=>' Item expired ',
        406=>' Incorrect item received ',
        407=>' Item no longer needed ',
        408=>' Not specified ',
        409=>' Buyer ordered a wrong item ',
        410=>' Other ',
        411=>' Items quality was not the exprected ',
        412=>' Item received too late ',
        413=>' Item undeliverable '
    ];

    public function __construct()
    {
        $options = $this->requestRefreshToken();
        $this->client = new Client($options);
    }

    private
    function requestRefreshToken()
    {
        $options = [
            'headers' => [
                'Accept' => 'applictaion/json',
                'Content-Type' => 'applictaion/json',
                'Authorization' => 'Basic ' . base64_encode('188ta4w0v0891byxxaql0emuep50irfd:5vbxC2W9T0SusLSF2e_Z0g')
            ]
        ];

        $client = new Client($options);
        $response = $client->post("https://api.channeladvisor.com/oauth2/token", [
            "form_params" => [
                "grant_type" => "refresh_token",
                "refresh_token" => "Y1ls4TeWjY5n_yU2_3wh84A7-WidgIcfSJPlk11eTV0"
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $auth = json_decode($response->getBody()->getContents(), true);
            $this->auth = $auth;
            $options['headers']['Authorization'] = "Bearer " . $auth['access_token'];
            return $options;
        }
    }

    public
    function getProducts($skip)
    {
        try {
            if ($skip !== null) {
                $skip = "&\$skip=$skip";
            }
            $response = $this->client->request('GET', "https://api.channeladvisor.com/v1/Products?\$expand=DCQuantities(\$filter=DistributionCenterID eq -2)$skip");
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            } else {
                return "Error occured.";
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public
    function getProduct($id)
    {
        try {
            $response = $this->client->request('GET', "https://api.channeladvisor.com/v1/Products($id)");
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            } else {
                return "Error occured.";
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public
    function getProductBySku($sku)
    {
        try {
            $response = $this->client->request('GET', "https://api.channeladvisor.com/v1/Products?\$filter=Sku eq '$sku'&\$expand=DCQuantities");
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            } else {
               return "Error occured.";
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public
    function getOrder($id) {
        try {
            $query = "https://api.channeladvisor.com/v1/Orders?\$filter=SiteOrderID eq '$id'&\$expand=Items(\$expand=Adjustments, BundleComponents),Fulfillments, Adjustments";
            $response = $this->client->request('GET', $query);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } else {
                return "Error occured.";
            }
        } catch (\Exception $exception) {
            if ($exception->getCode() === 503) {
                $this->requestRefreshToken();
                $this->getOrder($id);
            } else {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public
    function getOrders($param, $skip = null) {
        $filter = $this->filterString($param);
        if ($skip !== null) {
            $filter = "$filter&\$skip=$skip";
        }

        try {
            $query = "https://api.channeladvisor.com/v1/Orders$filter&\$expand=Items,Fulfillments";
            echo $query."\r\n";
            $response = $this->client->request('GET', $query);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } else {
                return "Error occured.";
            }
        } catch (\Exception $exception) {
            if ($exception->getCode() === 503) {
                $this->requestRefreshToken();
                $this->getOrders($param, $skip);
            } else {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public
    function getOrderAdjustments($param, $skip = null) {
        if ($skip !== null) {
            $skip = "&\$skip=$skip";
        }

        try {
            $query = "https://api.channeladvisor.com/v1/OrderItems?&\$filter=Adjustments/Any(c:c/CreatedDateUtc gt {$param['created_at_min']})&\$select=Order&\$expand=Order(\$expand=Fulfillments(\$expand=Items),Fulfillments(\$filter=DistributionCenterID lt 0), Items(\$expand=Adjustments))$skip";
            echo $query."\r\n";
            $response = $this->client->request('GET', $query);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } else {
                return "Error occured.";
            }
        } catch (\Exception $exception) {
            if ($exception->getCode() === 503) {
                $this->requestRefreshToken();
                $this->getOrders($param, $skip);
            } else {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    private function filterString($param) {
        $filterStringArr = [];
        foreach (array_filter($param) as $key => $value) {
            switch ($key) {
                case 'created_at_min':
                    $filterStringArr[] = "CreatedDateUtc ge $value";
                    break;
                case 'created_at_max':
                    $filterStringArr[] = "CreatedDateUtc lt $value";
                    break;
                case 'shipped_at_min':
                    $filterStringArr[] = "ShippingDateUtc ge $value";
                    break;
                case 'shipped_at_max':
                    $filterStringArr[] = "ShippingDateUtc lt $value";
                    break;
                case 'CheckoutStatus':
                    $filterStringArr[] = "CheckoutStatus lt '$value'";
                    break;
                default:
                    $filterStringArr[] = "{$key} eq {$value}";
                    break;
                }
        }
        return "?\$filter=".implode(' and ', $filterStringArr);
    }
}
