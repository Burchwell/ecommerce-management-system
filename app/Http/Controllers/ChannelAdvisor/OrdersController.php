<?php

namespace App\Http\Controllers\ChannelAdvisor;

use App\Helpers\ChannelAdvisor;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use App\Shipstation\ShipstationTag;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class OrdersController
 * @package App\Http\Controllers\ChannelAdvisor
 */
class OrdersController extends Controller
{

    /** @var string */
    private $api_token = 'Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $ca = new ChannelAdvisor();
            $order = ($ca->getOrder($id))['value'][0];
            $i=1;
            $sku_counts = [];
            $total_order_pc_ct = 0;
            $total_order_unique_sku_ct = 0;
            $calculated_total_order_weight_lbs = 0;
            $curSku = "";

            $tag_id = explode(',', $request->get('tagID'));
            $db_tags = ShipstationTag::whereIn('tag_id', $tag_id)->where('autoprint_trigger_tag', '=', 'yes')->count();

            $does_autoprint_tag_exist = 'Yes';

            if ($db_tags === 0) {
                $does_autoprint_tag_exist = 'No';
            }

            foreach ($order['Items'] as $item) {
                if (empty($item['BundleComponents'])) {
                    $product = Product::where('sku', '=', $item['Sku'])->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->with('inventory')->first();
                    $sku_counts["unique_sku_{$i}"] = $item['Sku'];
                    $sku_counts["unique_sku_{$i}_quantity"] = $item['Quantity'];
                    $total_order_pc_ct += $item['Quantity'];

                    // Inventory
                    $sku_counts["unique_sku_{$i}_tampa_wh_qty"] = $product->inventory->where('warehouse_id', '=', 1)->pluck('quantity')[0] ?: 0;
                    $sku_counts["unique_sku_{$i}_las_vegas_wh_qty"] = $product->inventory->where('warehouse_id', '=', 2)->pluck('quantity')[0] ?: 0;

                    // Warehouse Location
                    $sku_counts["unique_sku_{$i}_warehouse_location"] = $product->location;

                    // Weight
                    $sku_counts["unique_sku_{$i}_weight_per_unit_lbs"] = $product->weight;
                    $sku_counts["unique_sku_{$i}_weight_total_qty_lbs"] = ($product->weight * $item['Quantity']);
                    $calculated_total_order_weight_lbs += ($product->weight * $item['Quantity']);

                    // Dimensions
                    $sku_counts["unique_sku_{$i}_length_in"] = $product->length;
                    $sku_counts["unique_sku_{$i}_width_in"] = $product->width;
                    $sku_counts["unique_sku_{$i}_height_in"] = $product->height;

                    if ($item['Sku'] !== $curSku) {
                        $curSku = $item['Sku'];
                        $total_order_unique_sku_ct++;
                    }

                    $i++;
                } else {
                    foreach ($item['BundleComponents'] as $component) {
                        $product = Product::where('sku', '=', $component['Sku'])->with(['inventory' => function ($q) {
                            $q->without('warehouse');
                        }])->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->first();

                        $sku_counts["unique_sku_{$i}"] = $component['Sku'];
                        $sku_counts["unique_sku_{$i}_quantity"] = $component['Quantity'];
                        $sku_counts["unique_sku_{$i}_bundle_sku"] = $component['BundleSku'];

                        // Inventory
                        $sku_counts["unique_sku_{$i}_tampa_wh_qty"] = $product->inventory()->where('warehouse_id', '=',1)->first('quantity')->quantity;
                        $sku_counts["unique_sku_{$i}_las_vegas_wh_qty"] = $product->inventory()->where('warehouse_id', '=',2)->first('quantity')->quantity;

                        // Warehouse Location
                        $sku_counts["unique_sku_{$i}_warehouse_location"] = $product->location;


                        // Weight
                        $sku_counts["unique_sku_{$i}_weight_per_unit_lbs"] = $product->weight;
                        $sku_counts["unique_sku_{$i}_weight_total_qty_lbs"] = ($product->weight * $component['Quantity']);
                        $calculated_total_order_weight_lbs += ($product->weight * $component['Quantity']);

                        // Dimensions
                        $sku_counts["unique_sku_{$i}_length_in"] = $product->length;
                        $sku_counts["unique_sku_{$i}_width_in"] = $product->width;
                        $sku_counts["unique_sku_{$i}_height_in"] = $product->height;

                        $total_order_pc_ct += $component['Quantity'];

                        if ($item['Sku'] !== $curSku) {
                            $curSku = $item['Sku'];
                            $total_order_unique_sku_ct++;
                        }
                        $i++;
                    }
                }
// Disabled as per Kevin 09/25/2020
                $client = new Client();
                $url = sprintf('https://nova.skaraudio.com/api/order/%s?api_token=%s', $order['SiteOrderID'], $this->api_token);
                $resposne = $client->get($url);
                $nova_order = json_decode($resposne->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

                $totalGirth = $this->_calculateTotalGirth($order);
                $single_pkg_girth_within_constraints = $totalGirth <= 130 ? true : false;

                $cubic_root_calculated_dimension_in = Round($this->_calculateTotalVolume($order),2);
                $single_pkg_volume_within_constraints = $cubic_root_calculated_dimension_in <= 130 ? true : false;

                $shipstation_id = $nova_order['orderId'];
                $calculated_total_order_weight_lbs = round($calculated_total_order_weight_lbs, 2);
            }

            return response()->json(compact( 'order', 'sku_counts', 'calculated_total_order_weight_lbs', 'does_autoprint_tag_exist', 'total_order_pc_ct', 'total_order_unique_sku_ct','single_pkg_girth_within_constraints','totalGirth','cubic_root_calculated_dimension_in','single_pkg_volume_within_constraints' ));
        } catch (Exception $e) {
            return response()->json($e->getTrace());
        }
    }
    private function _calculateTotalVolume($order) {
        $totalVolume = 0;
        foreach($order['Items'] as $orderProduct) {
            if (empty($orderProduct['BundleComponents'])) {
                $product = Product::where('sku', '=', str_replace(Product::$clean, '', $orderProduct['Sku']))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->first();
                $totalVolume += (($product->width * $product->height * $product->length) * $orderProduct['Quantity']);
            } else {
                foreach ($orderProduct['BundleComponents'] as $component) {
                    $product = Product::where('sku', '=', str_replace(Product::$clean, '', $component['Sku']))->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->first();
                    $totalVolume += (($product->width * $product->height * $product->length) * $orderProduct['Quantity']);
                }
            }
        }
        return round($totalVolume ** (1 / 3));
    }
    private function _calculateTotalGirth($order)
    {
        $totalGirth = 0;
        foreach ($order['Items'] as $item) {
            if (empty($item['BundleComponents'])) {
                $product = Product::where('sku', '=', $item['Sku'])->select('id', 'sku', 'weight', 'length', 'width', 'height', 'location')->with('inventory')->first();
                $totalGirth += (($product->width) * 2 + ($product->height * 2)) * $item['Quantity'];
            }else{
                foreach ($item['BundleComponents'] as $component) {
                $product = Product::where('sku', '=', $component['Sku'])->with('inventory')->select('weight', 'length', 'width', 'height')->first();
                    $totalGirth += (($product->width) * 2 + ($product->height * 2)) * $item['Quantity'];
                }
            }
            return $totalGirth;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
