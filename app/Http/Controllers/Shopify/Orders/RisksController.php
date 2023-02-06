<?php

namespace App\Http\Controllers\Shopify\Orders;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shopify\ShopifyController;
use Illuminate\Http\Request;

class RisksController extends Controller
{
    public function index($order_id = NULL) {
        $risks = $this->shopify->Order($order_id)->Risk->get();
        return response()->json(compact('risks'));
    }

    public function view($order_id, $risk_id) {
        $risk = $this->shopify->Order($order_id)->Risk($risk_id)->get();
        return response()->json(compact('risk'));
    }
}
