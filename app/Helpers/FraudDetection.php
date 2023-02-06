<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Helpers\FraudDetection
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FraudDetection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FraudDetection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FraudDetection query()
 * @mixin \Eloquent
 */
class FraudDetection extends Model
{
    private $score = 0;

    // Check IP Address Origin Outside US && Location vs Billing Address
    // Check if proxy headers present
    // Verify Billing Address
    // Total Purchase Attempts
    // Check Billing Address Match Shipping Address
    // Check Total Order Value
    // Check Item Quantities && Item Value
    // Check AVS
    // Check CVV
    // Shopify Risk Level

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getScore() {
        return $this->score;
    }

    public function validateOrder($orderNumber, Request $request) {
        $order = $this->shopify->Order($orderNumber)->get();
        $valid = true;
        $validationErrors = [];


        if (
            $order['billing_address']['latitude'] !== $order['shipping_address']['latitude'] ||
            $order['billing_address']['longitude'] === $order['shipping_address']['longitude']
        ) {
            $validationErrors[] = 'Billing address doesn\'t match shipping address';
        }

        if ($order['payment_details']['avs_result_code'] !== 'Y') {
            $validationErrors[] = 'Address Verification (AVS) failed.';
        }

        if ($order['payment_details']['cvv_result_code'] !== 'M') {
            $validationErrors[] = 'Credit Card Verification (CVV) failed.';
        }

        if (count($validationErrors) !== 0) {
            $valid = false;
            return response()->json(compact('valid', 'validationErrors'));
        }

        return response()->json(compact('valid', 'order'));

    }

}
