<?php

namespace App\Http\Controllers\Shopify;

use App\Helpers\ShipEngine;
use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Models\Shopify\Customer;
use App\Shipstation\ShipstationTag;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPShopify\CurlRequest;
use PHPShopify\Exception\ApiException;

/**
 * Class OrdersController
 * @package App\Http\Controllers\Shopify
 */
class OrdersController extends Controller
{
    public const REGEX = "/[^a-zA-Z0-9]/";
    /** @var string */
    private $api_token = 'Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK';

    private $calculated_total_order_weight_lbs = 0;

    public function index()
    {
        $orders = $this->shopify->Order()->get();
        return response()->json(compact('orders'));
    }

    public function view($order_id, Request $request)
    {

        $tag_id = explode(',', $request->get('tagID'));
//        $db_tags = ShipstationTag::whereIn('tag_id', $tag_id)->where('autoprint_trigger_tag', '=', 'yes')->count();
        $does_autoprint_tag_exist = 'Yes';

        $does_autoprint_tag_exist = null;
//        if ($db_tags === 0) {
//            $does_autoprint_tag_exist = 'No';
//        }

        $orders = $this->shopify->Order->get(['name' => $order_id, 'status' => 'any']);

        if (empty($orders)) {
            return response()->json(["error" => "not found"], CurlRequest::$lastHttpCode);
        }

        $order = $orders[0];

        $transactionCt = $this->shopify->Order($order['id'])->Transaction->count();
        $transactions = $this->shopify->Order($order['id'])->Transaction->get();
        $risks = $this->shopify->Order($order['id'])->Risk->get();

        $order['transactions'] = [
            'count' => $transactionCt,
            'transactions' => $transactions
        ];


        $sku_counts_res = $this->_getSkuCountsWeightAndDimensions($order);
        $sku_counts = $sku_counts_res['skuCount'];
        $is_dealer_order = $sku_counts_res['is_dealer_order'];

        $transaction_info = null;
        $transaction_info_total_payment_attempts = null;
        $transaction_info_unique_payment_types_attempted = null;
        $transaction_info_is_success_attempt_prepaid_card_type = null;

        if ($order['gateway'] === 'shopify_payments') {
            $res = $this->_getTransactionData($transactions);
            $transaction_info = $res['res'];
            $transaction_info_total_payment_attempts = $res['transaction_info_total_payment_attempts'] ?? 0;
            $transaction_info_unique_payment_types_attempted = $res['transaction_info_unique_payment_types_attempted'] ?? 0;
            $transaction_info_is_success_attempt_prepaid_card_type = $res['transaction_info_is_success_attempt_prepaid_card_type'] ?? 'No';
        }

//        $client = new Client();
//        $url = sprintf('https://nova.skaraudio.com/api/order/%s?api_token=%s', $order_id, $this->api_token);
//        $resposne = $client->get($url);
//        $nova_order = json_decode($resposne->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
//
//        $shipstation_id = $nova_order['orderId'];

        $order['risks'] = $risks;

        $customer = $this->shopify->Customer($order['customer']['id'])->get();
//        $total_spend = $customer['total_spent'];

        $address_match = $this->_compareShippingBillingAddress($order);

        $geo_match = ($order['billing_address']['latitude'] === $order['shipping_address']['latitude'] && $order['billing_address']['longitude'] === $order['shipping_address']['longitude']);

        $is_paypal = ($order['gateway'] === 'paypal');
        $is_apple_pay = $this->isWalletPayment($order['transactions']['transactions'], 'apple_pay');
        $is_google_pay = $this->isWalletPayment($order['transactions']['transactions'], 'google_pay');

        $inventory = [];
        $total_order_pc_ct = 0;
        $total_order_unique_sku_ct = 0;
        $curSku = "";

        foreach ($order['line_items'] as $item) {
            if ($item['sku'] !== $curSku) {
                $curSku = $item['sku'];
                $total_order_unique_sku_ct++;
            }

            $res = ((Product::where('sku', '=', str_replace('DLR-', '', $item['sku']))->first())->inventory()->with('warehouse')->get())->toArray();

            $iv = [];
            foreach ($res as $wh_iv) {
                if ($wh_iv['warehouse_id'] !== 3) {
                    $iv[$wh_iv['warehouse']['slug']] = $wh_iv['quantity'];
                }
            }
            $inventory[] = array_merge(['sku' => $item['sku']], $iv);

            $total_order_pc_ct += $item['quantity'];


        }

        $ship_address_verification = $this->validateAddress($order['shipping_address']);
        $address_verified = ($ship_address_verification->status !== "error");
        $shipment_status = [];
        foreach ($order['fulfillments'] as $fulfillment) {
            $shipment = ShipEngine::getShipmentStatus($fulfillment['tracking_company'], $fulfillment['tracking_number']);
            $shipment['carrier'] = $fulfillment['tracking_company'];

            $shipment_status[] = $shipment;
        }

        // As per Kevin disabled 09/25/2020
//        $client = new Client();
//        $response = $client->get("https://nova.skaraudio.com/api/order/{$order_id}?api_token=Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK");
// Disabled as per Kevins Request 09/25/2020
        //        $nova = [];
//        if ($response->getStatusCode() === 200) {
//            $nova = json_decode($response->getBody()->getContents(), true);
//        }

        $billing_address_po_box = $this->yesNo($this->contains($order['billing_address']['address1'], ["P.O. Box", "PO Box", "Box", "P O Box", "P.O Box"]));
        $shipping_address_po_box = $this->yesNo($this->contains($order['shipping_address']['address1'], ["P.O. Box", "PO Box", "Box", "P O Box", "P.O Box"]));

        $order_billing_shipping_state_match = $order['billing_address']['province'] == $order['shipping_address']['province'] ? 'Yes' : 'No';

        $first_name_match = $this->yesNo(strtolower(preg_replace(self::REGEX, "", $order['billing_address']['first_name'])) === strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $order['shipping_address']['first_name'])));
        $last_name_match = $this->yesNo(strtolower(preg_replace(self::REGEX, "", $order['billing_address']['last_name'])) === strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $order['shipping_address']['last_name'])));
        $customer_rolling_age = $this->dateDifference($customer['updated_at'], $customer['created_at']);
        $calculated_total_order_weight_lbs = $this->calculated_total_order_weight_lbs;

        $totalGirth = $this->_calculateTotalGirth($order);
        $single_pkg_girth_within_constraints = $totalGirth <= 18 ? true : false;
        $cubic_root_calculated_dimension_in = Round($this->_calculateTotalVolume($order), 2);
        $single_pkg_volume_within_constraints = $cubic_root_calculated_dimension_in <= 130 ? true : false;

        $ip_to_ship_distance_miles = $this->getDistance($order);

        if (!isset($transaction_info)) {
            $transaction_info = null;
        }

        return response()->json(compact(
//                'shipstation_id',
                'order',
                'customer',
                'inventory',
                'address_match',
                'geo_match',
                'address_verified',
                'is_paypal',
                'is_apple_pay',
                'is_google_pay',
                'is_dealer_order',
                'ip_to_ship_distance_miles',
                'shipment_status',
                'billing_address_po_box',
                'shipping_address_po_box',
                'order_billing_shipping_state_match',
                'first_name_match',
                'last_name_match',
                'customer_rolling_age',
                'sku_counts',
                'calculated_total_order_weight_lbs',
                'does_autoprint_tag_exist',
                'total_order_pc_ct',
                'total_order_unique_sku_ct',
                'totalGirth',
                'single_pkg_girth_within_constraints',
                'cubic_root_calculated_dimension_in',
                'single_pkg_volume_within_constraints',
                'transaction_info',
                'transaction_info_total_payment_attempts',
                'transaction_info_unique_payment_types_attempted',
                'transaction_info_is_success_attempt_prepaid_card_type')
        );
    }


    private function _calculateTotalVolume($order)
    {
        $totalVolume = 0;
        foreach ($order['line_items'] as $orderProduct) {
            $product = Product::where('sku', '=', str_replace(Product::$clean, '', $orderProduct['sku']))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->first();

            $totalVolume += (($product->width * $product->height * $product->length) * $orderProduct['quantity']);
        }
        return round($totalVolume ** (1 / 3));
    }

    private function _calculateTotalGirth($order)
    {
        $totalGirth = 0;
        foreach ($order['line_items'] as $orderProduct) {
            $product = Product::where('sku', '=', str_replace(Product::$clean, '', $orderProduct['sku']))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->first();

            $totalGirth += (($product->width) * 2 + ($product->height * 2)) * $orderProduct['quantity'];
        }
        return $totalGirth;
    }


    private function _getSkuCountsWeightAndDimensions($order)
    {

        $skuCount = [];
        $uniqeProducts = [];
        $is_dealer_order = false;

        foreach ($order['line_items'] as $orderProduct) {
            if (isset($uniqeProducts[$orderProduct['sku']])) {
                $uniqeProducts[$orderProduct['sku']] += $orderProduct['quantity'];
            } else {
                $uniqeProducts[$orderProduct['sku']] = $orderProduct['quantity'];
            }
        }

        $i = 1;

        foreach ($uniqeProducts as $key => $quantity) {
            $product = Product::where('sku', '=', str_replace(Product::$clean, '', $key))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location', 'is_finished_bundle')->with('inventory')->first();
            if (strpos($key, 'BNDL') === 0 && $product->is_finished_bundle === "false") {
                $bundleProducts = (array)DB::table('bundle_mappings')->where('bundle_sku', '=', $key)->first();
                for ($j = 1; $j < 5; $j++) {
                    if ($bundleProducts['child_sku_' . $j] !== null) {
                        $product = Product::where('sku', '=', str_replace(Product::$clean, '', $bundleProducts['child_sku_' . $j]))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location', 'is_finished_bundle')->with('inventory')->first();
                        $skuCount['unique_sku_' . $i] = $bundleProducts['child_sku_' . $j];
                        $skuCount['unique_sku_' . $i . '_quantity'] = $quantity;
                        $skuCount['unique_sku_' . $i . '_bundle_sku'] = $bundleProducts['bundle_sku'];

                        // Warehouse Location
                        $skuCount["unique_sku_{$i}_warehouse_location"] = $product->location;
                        // Inventory
                        $skuCount["unique_sku_{$i}_tampa_wh_qty"] = optional($product->inventory()->where('warehouse_id', '=', 1)->pluck('quantity'))[0] ?: 0;
                        $skuCount["unique_sku_{$i}_las_vegas_wh_qty"] = optional($product->inventory()->where('warehouse_id', '=', 2)->pluck('quantity'))[0] ?: 0;

                        // Weight
                        $skuCount["unique_sku_{$i}_weight_per_unit_lbs"] = $product->weight;
                        $skuCount["unique_sku_{$i}_weight_total_qty_lbs"] = ($product->weight * $quantity);
                        $this->calculated_total_order_weight_lbs += round(($product->weight * $quantity), 2);

                        // Dimensions
                        $skuCount["unique_sku_{$i}_length_in"] = $product->length;
                        $skuCount["unique_sku_{$i}_width_in"] = $product->width;
                        $skuCount["unique_sku_{$i}_height_in"] = $product->height;
                        $i++;
                    }
                }
            } else {
                $skuCount['unique_sku_' . $i] = $key;
                $skuCount['unique_sku_' . $i . '_quantity'] = $quantity;

                // Warehouse Location
                $skuCount["unique_sku_{$i}_warehouse_location"] = $product->location;
                // Inventory
                $skuCount["unique_sku_{$i}_tampa_wh_qty"] = optional($product->inventory()->where('warehouse_id', '=', 1)->pluck('quantity'))[0] ?: 0;
                $skuCount["unique_sku_{$i}_las_vegas_wh_qty"] = optional($product->inventory()->where('warehouse_id', '=', 2)->pluck('quantity'))[0] ?: 0;

                // Weight
                $skuCount["unique_sku_{$i}_weight_per_unit_lbs"] = $product->weight;
                $skuCount["unique_sku_{$i}_weight_total_qty_lbs"] = ($product->weight * $quantity);
                $this->calculated_total_order_weight_lbs += round(($product->weight * $quantity), 2);

                // Dimensions
                $skuCount["unique_sku_{$i}_length_in"] = $product->length;
                $skuCount["unique_sku_{$i}_width_in"] = $product->width;
                $skuCount["unique_sku_{$i}_height_in"] = $product->height;
                $i++;
            }

            if (strpos($key, 'DLR-') !== true) {
                $is_dealer_order = true;
            }
        }


        return compact('skuCount', 'is_dealer_order');
    }

    //parse transactions and get key data from it
    private function _getTransactionData($transaction_info)
    {
        $res = [];
        $transaction_info_unique_payment_types_attempted = 0;
        $transaction_info_is_success_attempt_prepaid_card_type = null;
        $curPaymentType = null;
        if (isset($transaction_info) && count($transaction_info) > 0) {
            $i = 1;
            foreach ($transaction_info as $transaction) {
                if ($transaction['kind'] !== 'authorization') {
                    continue;
                }
                $res['attempt_' . $i] = [
                    'success' => $transaction['status'] === 'success' ? 'Yes' : 'No',
                    'receipt' =>
                        [
                            'result' => $transaction['status'],
                            'message' => $transaction['message'],
                            'param' => isset($transaction['receipt']['error']['param']) ? $transaction['receipt']['error']['param'] : '',
                            'type' => isset($transaction['receipt']['error']['type']) ? $transaction['receipt']['error']['type'] : '',
                            'code' => $transaction['error_code'],
                        ],
                    'avs_code' => isset($transaction['payment_details']['avs_result_code']) ? $transaction['payment_details']['avs_result_code'] : '',
                    'cvv_code' => isset($transaction['payment_details']['cvv_result_code']) ? $transaction['payment_details']['cvv_result_code'] : '',
                    'credit_card_type' => isset($transaction['payment_details']['credit_card_company']) ? $transaction['payment_details']['credit_card_company'] : '',
                    'credit_card_number' => isset($transaction['payment_details']['credit_card_number']) ? $transaction['payment_details']['credit_card_number'] : '',
                    'card_last_four' => isset($transaction['receipt']['charges']['data'][0]['payment_method_details']['card']['last4']) ? $transaction['receipt']['charges']['data'][0]['payment_method_details']['card']['last4'] : '',
                    'funding_type' => isset($transaction['receipt']['charges']['data'][0]['payment_method_details']['card']['funding']) ? $transaction['receipt']['charges']['data'][0]['payment_method_details']['card']['funding'] : '',
                    'name_on_card' => isset($transaction['receipt']['credit_card_number']) ? $transaction['payment_details']['credit_card_number'] : '',
                ];

                if ($curPaymentType !== $res['attempt_' . $i]['credit_card_number']) {
                    $curPaymentType = $res['attempt_' . $i]['credit_card_number'];
                    $transaction_info_unique_payment_types_attempted++;
                }

                if ($res['attempt_' . $i]['success'] === 'Yes' && strtolower($res['attempt_' . $i]['funding_type']) === 'prepaid') {
                    $transaction_info_is_success_attempt_prepaid_card_type = 'Yes';
                }

                $i++;
            }
        }
        $transaction_info_total_payment_attempts = $i-1;
        return compact('res', 'transaction_info_total_payment_attempts', 'transaction_info_unique_payment_types_attempted', 'transaction_info_is_success_attempt_prepaid_card_type');
    }

    public function order($order_id)
    {
        $order = $this->shopify->Order($order_id)->get();
        return response()->json(compact('order'));
    }

    public function validateAddress($address)
    {
        $client = new Client(
            [
                "headers" => [
                    'API-Key' => 'wFPKl5IvmNlz8YLLvMUnWPuOyEhNusHaEgLSzYMwBcA'
                ]
            ]
        );

        $request = [
            "json" => [
                [
                    "name" => $address['name'],
                    "phone" => $address['phone'],
                    "company_name" => $address['company'],
                    "address_line1" => $address['address1'],
                    "address_line2" => $address['address2'] ?: null,
                    "city_locality" => $address['city'],
                    "state_province" => $address['province_code'],
                    "postal_code" => $address['zip'],
                    "country_code" => $address['country_code'],
                ]
            ]
        ];

        if (isset($address['address3'])) {
            $request['json']['address_line3'] = $address['address3'];
        }

        $response = $client->post('https://api.shipengine.com/v1/addresses/validate',
            $request
        );

        return json_decode($response->getBody()->getContents())[0];
    }

    //Compare the address fields from shipping and billing address
    private function _compareShippingBillingAddress($order): bool
    {

        $compareFields = ['address1', 'zip', 'province', 'city'];

        $address_match = true;
        $match = [];
        foreach ($compareFields as $compare) {
            if ($order['billing_address'][$compare] !== $order['shipping_address'][$compare]) {
                $address_match = false;

                break;
            }
        }

        return $address_match;
    }

    public function getInventory($sku = NULL)
    {
        $uri = "https://files.channable.com/lavSFgWcbDHo3w6gf2nVmQ==.csv";
        $data = file_get_contents($uri);
        $rows = explode("\n", $data);
        $s = array();
        foreach ($rows as $row) {
            $row = str_getcsv($row);
            $s[] = $row;
            if ($row[0] === str_replace('DLR-', '', $sku)) {
                return $row;
            }
        }
        return false;
    }

    private
    function isWalletPayment($transactions, $type)
    {
        $isType = false;
        foreach ($transactions as $transaction) {
            if (isset($transaction['receipt']['charges']['data'])) {
                foreach ($transaction['receipt']['charges']['data'] as $data) {
                    if (!isset($transaction['receipt']['payment_method_details'])) {
                        continue;
                    }
                    $wallet = $data['payment_method_details']['card']['wallet'];
                    if ($wallet !== null && $wallet['type'] === $type) {
                        $isType = true;
                    }
                }
            } else {
                if (!isset($transaction['receipt']['payment_method_details'])) {
                    continue;
                }
                $wallet = ($transaction['receipt']['payment_method_details']['card']['wallet']);
                if ($wallet !== null && $wallet['type'] === $type) {
                    $isType = true;
                }
            }
        }
        return $isType;
    }

    private
    function getDistance($order) {
        $client = new Client();
        $request = $client->request('get', "http://api.ipstack.com/{$order['browser_ip']}?access_key=830560663822e6a845b1fe09e078bba4");
        $response = json_decode($request->getBody()->getContents(), true);
        $distance = $this->vincentyGreatCircleDistance($order['shipping_address']['latitude'], $order['shipping_address']['longitude'], $response['latitude'], $response['longitude']);
        return round($distance, 1);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param int $earthRadius Mean earth radius in [miles]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    private
    function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = ((cos($latTo) * sin($lonDelta)) ** 2) +
            ((cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta)) ** 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
