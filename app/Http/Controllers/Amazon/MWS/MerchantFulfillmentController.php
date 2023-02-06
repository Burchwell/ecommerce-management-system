<?php

namespace App\Http\Controllers\Amazon\MWS;

use App\Http\Controllers\Amazon\MwsController;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class MerchantFulfillmentController
 * @package App\Http\Controllers\Amazon\MWS
 */
class MerchantFulfillmentController extends MwsController
{

    /**
     * @param $amazonOrderId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEligibleShippingService($amazonOrderId, Request $request)
    {
        $optionalParams = $request->all();
        $optionalParams['ShipmentRequestDetails.AmazonOrderId'] = $amazonOrderId;

        if (isset($optionalParams['MustArriveByDate'])) {
            $optionalParams['MustArriveByDate'] = Carbon::parse($optionalParams['MustArriveByDate'])->format('c');
        }

        if (isset($optionalParams['ShipDate'])) {
            $optionalParams['ShipDate'] = Carbon::parse($optionalParams['MustArriveByDate'])->format('c');
        }

        $service = $this->Client->send('GetEligibleShippingServices', '/MerchantFulfillment/2015-06-01', $optionalParams);
        return response()->json($service);
    }

    /**
     * @param $amazonOrderId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAdditionalSellerInputs($amazonOrderId, Request $request)
    {
        $optionalParams = $request->all();
        $optionalParams['OrderId'] = $amazonOrderId;


//        return response()->json($optionalParams);
        $service = $this->Client->send('GetEligibleShippingServices', '/MerchantFulfillment/2015-06-01', $optionalParams);
        return response()->json($service);
    }
}
