<?php

namespace App\Http\Controllers\Shopify\Orders;

use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    public function index($order_id = NULL) {
        $transactions = $this->shopify->Order($order_id)->Transaction->generateUrl();
        return response()->json(compact('transactions'));
    }

    public function count($order_id) {
        $count = $this->shopify->Order($order_id)->Transaction->count();
        return response()->json(compact('count'));
    }

    public function view($order_id, $transaction_id) {
        $transaction = $this->shopify->Order($order_id)->Transaction($transaction_id)->get();
        return response()->json(compact('transaction'));
    }

}
