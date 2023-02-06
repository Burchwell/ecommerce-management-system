<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {
        $products = $this->shopify->Product->get();
        return response()->json(compact('products'));
    }

    public function view($product_id) {
        $product = $this->shopify->Product($product_id)->get();
        return response()->json(compact('product'));
    }

    public function import() {
        $products = $this->shopify->Product->get();

    }
}
