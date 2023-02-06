<?php

namespace App\Http\Controllers;

use App\Jobs\updateShipstationTags;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelShipStation\Models\Order;
use LaravelShipStation\ShipStation;

/**
 * Class ShipstationController
 * @package App\Http\Controllers
 */
class ShipstationController extends Controller
{
    /**
     * @param Request $request
     * @param $orderNumber
     * @return JsonResponse
     */
    public function updateTags(Request $request, $orderNumber)
    {
        try {
            $tags = $request->get('tags');
            $orderNo = $orderNumber;
            updateShipstationTags::dispatchNow($tags, $orderNo);
            return response()->json(["status"=>"Shipstation Tag job dispatched."]);
        } catch (Exception $e) {
            return response()->json('Error occured.' . $e->getMessage(), 500);
        }
    }

    public function view(Request $request, $orderNumber) {
        dd($request->all());
    }

}
