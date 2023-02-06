<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Tracking;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Console\Input\Input;


/**
 * Class OrdersController
 * @package App\Http\Controllers\Orders
 */
class OrdersController extends Controller
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

    public function updateTracking($order_number = NULL) {
        if ($order_number !== NULL) {
            \Artisan::queue("orders:tracking:update --order_number=$order_number");
        } else {
            \Artisan::queue("orders:tracking:update");
        }

        return response()->json('Update triggerd.');
    }

}
