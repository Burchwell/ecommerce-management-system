<?php

namespace App\Http\Controllers;

use App\Helpers\ChannelAdvisor;
use App\Http\Resources\Products\FulfillmentResource;
use App\Http\Resources\Products\FulfillmentResourceCollection;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductResourceCollection;
use App\Models\Products\Product;
use App\Products\ProductSkuMapping;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use PHPShopify\ShopifySDK;

/**
 * Class ProductsController
 * @package App\Http\Controllers
 */
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $perPage = Request::input('perPage') ?: $this->defaultPerPage;
        $products = new ProductResourceCollection(ProductResource::collection(Product::paginate($perPage)));

        return response()->json(compact('products'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function inventory(): JsonResponse
    {
        $filters = Product::$filter;
        $perPage = Request::input('perPage') ?: $this->defaultPerPage;
        if (Request::has('search')) {
            $filters[] = ['sku', 'LIKE', Request::input('search') . '%'];
        }

        $products = Product::with(['inventory', 'turnAround'])
            ->select('id', 'sku', 'upc')
            ->where($filters)
            ->paginate($perPage);


        return response()->json(compact('products'));
    }

    public function skuMappings(Request $request)
    {
        try {
            $filters = Product::$filter;
            $perPage = Request::input('perPage') ?: $this->defaultPerPage;
            if (Request::has('filter') && Request::input('filter') !== 'null') {
                $filters[] = ['sku', 'LIKE', Request::input('filter') . '%'];
            }
            $products = Product::with(['skumappings'])
                ->where($filters)
                ->select('id', 'sku');


            if (Request::has('sortBy')) {
                $sortBy = Request::input('sortBy');
                $direction = Request::input('sortDesc') === true ? 'DESC' : 'ASC';
                $products->orderBy($sortBy, $direction);
            }

            $products = new ProductResourceCollection(ProductResource::collection($products
                ->paginate($perPage)
            ));

            return response()->json(compact('products'));
        } catch (Exception $e) {
            return response()->json(sprintf("%s:%s: %s", $e->getFile(), $e->getLine(), $e->getMessage()), $e->getCode());
        }
    }

    public function saveAllSkuMappings()
    {
        $param = Request::input();
        foreach ($param as $mpng) {
            ProductSkuMapping::updateOrCreate($mpng, $mpng);
        }

        return $this->skuMappings();
    }

    public function saveSkuMappings($id, \Illuminate\Http\Request $request)
    {
        $data = $request->all();
        foreach ($data['skumappings'] as $skuMapping) {
            $smap = array_filter($skuMapping);
            if (!empty($smap)) {
                Product::find($data['id'])->skuMappings()->updateOrCreate(
                    [
                        'product_id' => $data['id'],
                        'mapping' => $skuMapping['mapping'],
                        'source' => $skuMapping['source'] ?: 'all'
                    ]
                );
            }
        }

        return $this->skuMappings();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function fulfillments(): JsonResponse
    {
        $filters = Product::$filter;
        $request = array_filter(Request::input());

        $perPage = $request['perPage'] ?: $this->defaultPerPage;

        $results = Product::where($filters);

        $results->join('inventories as quantity_on_hand_tampa', function ($join) {
            $join->on('quantity_on_hand_tampa.product_id', '=', 'products.id');
            $join->on('quantity_on_hand_tampa.warehouse_id', '=', DB::raw(1));
        });

        $results->join('inventories as quantity_on_hand_las_vegas', function ($join) {
            $join->on('quantity_on_hand_las_vegas.product_id', '=', 'products.id');
            $join->on('quantity_on_hand_las_vegas.warehouse_id', '=', DB::raw(2));
        });

        $results->join('product_turn_arounds as turn_around_time_tampa', function ($join) {
            $join->on('turn_around_time_tampa.product_id', '=', 'products.id');
            $join->on('turn_around_time_tampa.warehouse_id', '=', DB::raw(1));
        });

        $results->join('product_turn_arounds as turn_around_time_las_vegas', function ($join) {
            $join->on('turn_around_time_las_vegas.product_id', '=', 'products.id');
            $join->on('turn_around_time_las_vegas.warehouse_id', '=', DB::raw(2));
        });

        $results->join('product_turn_arounds as turn_around_time_fba', function ($join) {
            $join->on('turn_around_time_fba.product_id', '=', 'products.id');
            $join->on('turn_around_time_fba.warehouse_id', '=', DB::raw(3));
        });

        $results->select([
            '*',
            'quantity_on_hand_tampa.quantity as quantity_on_hand_tampa',
            'quantity_on_hand_las_vegas.quantity as quantity_on_hand_las_vegas',
            'turn_around_time_tampa.quantity as turn_around_time_tampa',
            'turn_around_time_fba.quantity as turn_around_time_fba',
            'turn_around_time_las_vegas.quantity as turn_around_time_las_vegas',
            'turn_around_time_fba.quantity as turn_around_time_fba'
        ]);

        if (isset($request['sortBy'])) {
            $request['sortBy'] = $request['sortBy'] === 'turn_around_time_total' ? 'total_turn' : $request['sortBy'];
            $results->orderBy($request['sortBy'], (($request['sortDesc'] === 'true') ? 'DESC' : 'ASC'));
        }

        if (isset($request['filter']) && $request['filter'] !== "null") {
            $filters[] = ['sku', 'LIKE', "{$request['filter']}%"];
        }

        $productResource = FulfillmentResource::collection($results->where($filters)->paginate($perPage));
        $products = new FulfillmentResourceCollection($productResource);
        return response()->json(compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $products
     * @return JsonResponse
     */
    public function show(Product $products)
    {
        return response()->json(new ProductResource($products->getKey()));
    }

    public function search($search)
    {
        $perPage = Request::input('perPage') ?: 10;
        $products = Product::without('images', 'inventory')->where(
            [
                Product::$filter,
                ['sku', 'LIKE', "%{$search}%"]
            ])
            ->orWhere(
                [
                    Product::$filter,
                    ['id', '=', $search]
                ])
            ->without(['pallet', 'notes', 'variants'])->select('id', 'sku')->paginate($perPage);

        if ($products) {
            $products->each(function ($product) {
                $tools = $this->getToolsInventoryInfo($product);
                if ($tools !== null && $tools['inventory']['total_qty_oh'] !== null) {
                    Log::info(json_encode($tools));
                    $product->total_oh = (int)$tools['inventory']['total_qty_oh'];
                    $product->tpa_oh = $tools['inventory']['tpa_qty_oh'];
                    $product->las_oh = $tools['inventory']['lv_qty_oh'];
                    $product->fba_oh = $tools['fba_info']['fba_qty_oh'];
                    return $product;
                }
            });

            return response()->json(compact('products'));
        }

        return response()->json("Not found.");
    }

    /**
     * Display the specified resource.
     *
     * @param Product $products
     * @return JsonResponse
     */
    public function showBySku($sku, Request $request)
    {
        try {
            $product = Product::with('inventory', 'turnAround')->where('sku', '=', $sku)->first();
            $product->updated_at = $this->formatDT($product->updated_at);
            if (!empty($product)) {
                $inventory = [];
                $local_db = $product->toArray();
                foreach ($local_db['inventory'] as $i) {
                    if (array_key_exists($i['warehouse']['slug'], $inventory)) {
                        $inventory[$i['warehouse']['slug']] += $i['quantity'];
                    } else {
                        $inventory[$i['warehouse']['slug']] = $i['quantity'];
                    }
                }

                $inventory['last_updated'] = $this->formatDT($local_db['inventory'][0]['updated_at']);
                $local_db['inventory'] = $inventory;

                $last_update = 'N/A';

                if ($local_db !== null) {
                    $result = $this->getChannelAdvisorProductInfo($product);
                    $channel_advisor = array_key_exists('value', $result) ? $result['value'][0] : $result;
                    $shopify = $this->getShopifyProductInfo($product);
                    $tools = $this->getToolsInventoryInfo($product);

                    if (!empty($channel_advisor)) {
                        $channel_advisor['UpdateDateEst'] = $this->formatDT($channel_advisor['UpdateDateUtc']);
                        $channel_advisor['QuantityUpdateDateEst'] = $this->formatDT($channel_advisor['QuantityUpdateDateUtc']);
                        $channel_advisor_sales_snapshot = [
                            "QuantitySoldLast7Days" => $channel_advisor['QuantitySoldLast7Days'] ?: 0,
                            "QuantitySoldLast14Days" => $channel_advisor['QuantitySoldLast14Days'] ?: 0,
                            "QuantitySoldLast30Days" => $channel_advisor['QuantitySoldLast30Days'] ?: 0,
                            "QuantitySoldLast60Days" => $channel_advisor['QuantitySoldLast60Days'] ?: 0,
                            "QuantitySoldLast90Days" => $channel_advisor['QuantitySoldLast90Days'] ?: 0,
                            "last_update" => $this->formatDT($channel_advisor['UpdateDateUtc'])
                        ];
                    }

                    return response()->json(compact('local_db', 'tools', 'channel_advisor', 'shopify', 'channel_advisor_sales_snapshot'));
                }
            }
            return response()->json("product not found.", 400);
        } catch (Exception $e) {
            return response()->json(json_encode($e->getMessage()));
        }
    }

    public function getMapping($id) {
        $mappings = Product::find($id)->skuMappings()->get();
        return response()->json(compact('mappings'));
    }

    public function storeMapping($sku) {
        $data = Request::input();
        foreach ($data as $mapng) {
            if ($mapng !== null) {
                ProductSkuMapping::updateOrCreate(['product_id' => $sku, 'mapping' => $mapng['mapping']], $mapng);
            }
        }

        return $this->getMapping($sku);
    }

    public function deleteMapping($product_id, $id) {
        $deleted = ProductSkuMapping::destroy($id);
        return response()->json(compact('deleted'));
    }

    private function getChannelAdvisorProductInfo(Product $product)
    {
        try {
            $ca = new ChannelAdvisor();
            return ($product->channel_advisor_id !== null) ? $ca->getProduct($product->channel_advisor_id) : $ca->getProductBySku($product->sku);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function getShopifyProductInfo(Product $product)
    {
        try {
            $shopify = new ShopifySDK($config = [
                'ShopUrl' => config('shopify.store'),
                'ApiKey' => config('shopify.api_key'),
                'Password' => config('shopify.password')
            ]);
            return $shopify->Product($product->shopify_id)->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private
    function getToolsInventoryInfo(Product $product)
    {
        $tools = $this->getToolsInventoryData($product->sku);
        if ($tools !== null) {
            $res = (array)$this->getFbaData($product->sku)[0];
            $fba = [];
            $fba['fba_qty_oh'] = $res['warehouse'];
            $fba['fba_turn_30days'] = $res['turn'];
            $fba['fba_inbound'] = $res['inbound_receiving'] + $res['inbound_shipped'] + $res['inbound_working'];
            $fba["fba_velocity_peak_7days"] = "";
            $fba["fba_velocity_peak_30days"] = "";
            $fba["fba_last_updated"] = $this->formatDT($tools['fba_last_update']);

            return [
                "inventory" => [
                    "total_qty_oh" => $tools['quantity_on_hand'],
                    "tpa_qty_oh" => $tools['tpa1_oh'],
                    "lv_qty_oh" => $tools['las_oh'],
                    "fba_qty_oh" => $tools['fba_oh'],
                    "last_updated" => $this->formatDT($tools['tools_last_update'])
                ],
                "turn_info" => [
                    "tpa_turn_30days" => $tools['tpa_turn'],
                    "lv_turn_30days" => $tools['lv_turn'],
                    "fba_turn30days" => $tools['fba_turn'],
                    "wh_last_updated" => $this->formatDT($tools['tools_last_update']),
                    "fba_last_updated" => $this->formatDT($tools['fba_last_update'])
                ],
                "fba_info" => $fba
            ];
        }

        return $tools;
    }


    private
    function getToolsInventoryData($sku)
    {
        $tools = (array)DB::connection('tools')
            ->table('api_inventoryitemmodel')
            ->where('name', 'LIKE', "%$sku%")
            ->leftJoin('api_inventorysitemodel as tpa1', function ($join) {
                $join->on('tpa1.list_id', '=', 'api_inventoryitemmodel.list_id')
                    ->where('tpa1.site_name', '=', DB::raw("'Tampa WH 1'"));
            })
            ->leftJoin('api_inventorysitemodel as uss', function ($join) {
                $join->on('uss.list_id', '=', 'api_inventoryitemmodel.list_id')
                    ->where('uss.site_name', '=', DB::raw("'Unspecified Site'"));
            })
            ->leftJoin('api_inventorysitemodel as tpa2', function ($join) {
                $join->on('tpa2.list_id', '=', 'api_inventoryitemmodel.list_id')
                    ->where('tpa2.site_name', '=', DB::raw("'Tampa WH 2'"));
            })
            ->leftJoin('api_inventorysitemodel as las', function ($join) {
                $join->on('las.list_id', '=', 'api_inventoryitemmodel.list_id')
                    ->where('las.site_name', '=', DB::raw("'Las Vegas WH'"));
            })
            ->leftJoin('api_inventorysitemodel as fba', function ($join) {
                $join->on('fba.list_id', '=', 'api_inventoryitemmodel.list_id')
                    ->where('fba.site_name', '=', DB::raw("'Amazon FBA WH'"));
            })
            ->leftJoin('api_fbaitemmodel', 'api_fbaitemmodel.list_id', 'api_inventoryitemmodel.list_id')
            ->first(['sku', 'quantity_on_hand', 'tpa1.on_hand as tpa1_oh', 'tpa2.on_hand as tampa2_oh', 'las.on_hand as las_oh', 'fba.on_hand as fba_oh', 'uss.on_hand as uss_oh', 'api_inventoryitemmodel.turn as total_turn', 'lv_turn', 'api_fbaitemmodel.turn as fba_turn', 'api_fbaitemmodel.warehouse as fba_inventory', 'api_inventoryitemmodel.mod_timestamp as tools_last_update', 'api_inventoryitemmodel.mod_timestamp as tools_last_update', 'api_fbaitemmodel.mod_timestamp as fba_last_update']);

        if (!empty($tools)) {
            $tools['tpa_turn'] = $tools['total_turn'] - $tools['lv_turn'];
            return $tools;
        }
        return null;
    }

    private
    function getFbaData($sku)
    {
        return (DB::connection('tools')
            ->table('api_fbaitemmodel')
            ->where('sku', '=', $sku)
            ->get(['turn', 'pending_qty', 'fulfillable', 'inbound_receiving', 'inbound_shipped', 'inbound_working', 'reserved', 'total', 'unsellable', 'warehouse', 'processing', 'transfer'])
        )->toArray();
    }

    /**
     * UpdateJob the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $product = $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $products
     * @return JsonResponse
     */
    public function destroy(Product $products)
    {
        return response()->json($products->delete());
    }

    public function formatDT($dateTime)
    {
        $dt = new DateTime($dateTime, new DateTimeZone('UTC'));
        return ($dt->setTimezone(new DateTimeZone('America/New_York')))->format('Y-m-d\TH:i:s\Z');
    }
}
