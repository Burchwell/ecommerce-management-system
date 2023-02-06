<?php

namespace App\Http\Controllers\Amazon\MWS;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use ThiagoMarini\AmazonMwsClient;

/**
 * Class ordersController
 * @package App\Http\Controllers\Amazon\MWS
 */

class ProductsController extends Controller
{

    /** @var AmazonMwsClient  */
    protected $Client;

    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Client = new AmazonMwsClient(
            config('mws.access_key'),
            config('mws.private_key'),
            config('mws.seller_id'),
            [config('mws.marketplace_id')],
            config('mws.auth_token')
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listOrders(Request $request)
    {
        $optionalParams = $request->all();

        if (isset($optionalParams['CreatedAfter'])) {
            $optionalParams['CreatedAfter'] = Carbon::parse($optionalParams['CreatedAfter'])->format('c');
        }
        if (isset($optionalParams['LastUpdatedAfter'])) {
            $optionalParams['LastUpdatedAfter'] = Carbon::parse($optionalParams['LastUpdatedAfter'])->format('c');
        }

        if (!isset($optionalParams['LastUpdatedAfter']) && !isset($optionalParams['CreatedAfter'])) {
            $optionalParams['CreatedAfter'] = str_replace('+00:00', 'Z', gmdate('c', strtotime('yesterday')));
        }

        $orders = $this->Client->send('ListOrders', '/Orders/2013-09-01', $optionalParams);
        return response()->json($orders);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getServiceStatus()
    {
        $status = $this->Client->send('GetServiceStatus', '/Orders/2013-09-01');
        return response()->json($status);
    }

    /**
     * @param $amazonOrderId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrder($amazonOrderId, Request $request)
    {
        $optionalParams['AmazonOrderId.id.1'] = $amazonOrderId;
        $orders = $this->Client->send('GetOrder', '/Orders/2013-09-01', $optionalParams);
        return response()->json($orders);
    }

    /**
     * @param $amazonOrderId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listOrderItems($amazonOrderId, Request $request)
    {
        $optionalParams['AmazonOrderId'] = $amazonOrderId;
        $orders = $this->Client->send('ListOrderItems', '/Orders/2013-09-01', $optionalParams);
        return response()->json($orders);
    }
}
