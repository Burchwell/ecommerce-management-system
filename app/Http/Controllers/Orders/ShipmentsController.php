<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Tracking;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;



/**
 * Class OrdersController
 * @package App\Http\Controllers\Orders
 */
class ShipmentsController extends Controller
{
    use Tracking;

    public function index()
    {
        $perPage = Request::input('perPage') ?: 25;
        $orders = Order::paginate($perPage);

        return response()->json(compact('orders'));
    }

    public function view($id, Request $request) {
        $order = Order::with('items', 'adjustment','label')
            ->where('order_number', '=', $id)
            ->first();

        return response()->json(compact('order'));
    }

    public function validateOrder($orderNumber, Request $request)
    {
        Shopify::make(config('shopify.shop'), config('shopify.token'));
    }

    public function webhook(\Illuminate\Http\Request $request) {
        $shipments = $request->get('shipments');

        if ($shipments) {
            foreach ($shipments as $shipment) {
                $order = Order::where('order_number', '=', $shipment['orderNumber'])
                    ->first();

                if (is_object($order)) {
                    $trackingInfo = $this->_cleanUpTrackingInfo($shipment);
                    \Log::info("Trying to update Order #{$order->order_number} via Tracking #" . $trackingInfo['shipment_tracking_number']);
                    $trackingUpdates = $this->_getTrackingUpdates($trackingInfo['shipment_tracking_number']);
                    if ($trackingUpdates !== null) {
                        $trackingInfo += $trackingUpdates;
                    }

                    Order::where('order_number', '=', $shipment['orderNumber'])->update($trackingInfo);
                    $order->save();

                    \Log::info("Order # {$order->order_number} via Nova request updated.");
                } else {
                    \Log::warning("Order # {$shipment['orderNumber']} not found. Trying to retrieve from Source");
                    $order = $this->_getNovaData($shipment['orderNumber']);
                    if (is_object($order)) {
                        \Log::warning("Order # {$shipment['orderNumber']} retrieved from nova and stored in DB.");
                    } else {
                        \Log::warning("Order # {$shipment['orderNumber']} could not be retrieved.");
                    }
                }
            }
            return response()->json("All orders updated");
        } else {
            \Log::info("No shipment found.");
        }
    }
}
