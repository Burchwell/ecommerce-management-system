<?php

namespace App\Http\Controllers;

use App\FbaInboundShipmentProductPrep;
use App\Jobs\AmazonImportFBAShipmentsJob;
use App\Jobs\ImportFbaShipmentsJob;
use App\Jobs\UpdateFbaInboundShipment;
use App\Models\Amazon\FbaInboundShipment;
use App\Models\Amazon\FbaInboundShipmentProduct;
use App\Models\Products\Product;
use App\Models\Products\Warehouse;
use App\Traits\FbaFulfillmentTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * Class FbaInboundController
 * @package App\Http\Controllers
 */
class FbaInboundController extends Controller
{
    use FbaFulfillmentTrait;

    private $total_cbm = 0;
    private $total_weight = 0;
    private $total_pallets = 0;

    public function index()
    {
        $perPage = Request::input('perPage');
        $shipmentId = Request::input('filter');

        $query = FbaInboundShipment::with('warehouse')
            ->where('created_at', '>=', Carbon::now()->subDays('14')->format('c'));

        if (!empty($shipmentId) && $shipmentId !== 'null') {
            $query->where('shipment_id', 'LIKE', "$shipmentId%");
        }

        $query->orderBy('created_at', 'DESC');

        $shipments = $query->paginate($perPage);

        return response()->json(compact('shipments'));
    }

    public function show($shipmentId)
    {
        $shipment = FbaInboundShipment::with(['items' => function ($items) {
            $items->with(['product' => function ($q) {
                $q->select('id', 'sku', 'qpmc', 'max_fba_mc_qty', 'weight', 'length', 'width', 'height');
            }, 'prep'])->get();
        }, 'warehouse'])
            ->where('shipment_id', $shipmentId)
            ->first();

        return response()->json($shipment);
    }

    /**
     * Get the box labels from amazon
     */
    public function getBoxLabels($shipmentId) {
        $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
        $data = [
            'AWSAccessKeyId' => $this->_accessKey,
            'Action' => 'GetPackageLabels',
            'SellerId' => $this->_sellerId,
            'MWSAuthToken' => $this->_mwsAuthToken,
            'SignatureVersion' => 2,
            'Timestamp' => Carbon::now()->format('c'),
            'Version' => '2010-10-01',
            'SignatureMethod' => 'HmacSHA256',
            'ShipmentId' => $shipmentId ,
            'PageType'=>'PackageLabel_Thermal'
            ];

        $response = $this->sendRequest($data);

        $result = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);


        if(isset($result['GetPackageLabelsResult']['TransportDocument']['PdfDocument'])) {
            $pdf = $this->_getPdfFromZipFile($result['GetPackageLabelsResult']['TransportDocument']['PdfDocument']);

            return response($pdf)->withHeaders(['Content-Type'=> 'application/pdf']);
        }else {
            return response('Not found',404);
        }
    }

    /**
     * Get the box labels from amazon
     */
    public function getPalletLabels($shipmentId) {

        $shipment = FbaInboundShipment::where('shipment_id', $shipmentId)->first();

        if(!$shipment || $shipment->pallet_count <= 0) {
            return response('not found',404);
        }
        $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
        $data = [
            'AWSAccessKeyId' => $this->_accessKey,
            'Action' => 'GetPalletLabels',
            'SellerId' => $this->_sellerId,
            'MWSAuthToken' => $this->_mwsAuthToken,
            'SignatureVersion' => 2,
            'Timestamp' => Carbon::now()->format('c'),
            'Version' => '2010-10-01',
            'SignatureMethod' => 'HmacSHA256',
            'ShipmentId' => $shipmentId ,
            'PageType' => 'PackageLabel_Plain_Paper',
            'NumberOfPallets'=>$shipment->pallet_count,
        ];

        $response = $this->sendRequest($data);

        $result = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);


        if(isset($result["GetPalletLabelsResult"]["TransportDocument"]["PdfDocument"])) {
            $pdf = $this->_getPdfFromZipFile($result["GetPalletLabelsResult"]["TransportDocument"]["PdfDocument"]);

            return response($pdf)->withHeaders(['Content-Type'=> 'application/pdf']);
        }else {
            return response('Not found',404);
        }
    }

    //Base64decode zip
    //Extract zip
    //return contents
    private function _getPdfFromZipFile($zipFile) {
        $file = base64_decode($zipFile);
        $tmpFile = time().md5(rand(400,1000));

        //save zip to storage
        \Storage::disk('local')->put($tmpFile .'.zip', $file);

        //extract zip
        $zip = new \ZipArchive();
        $zip->open(storage_path().'/app/'. $tmpFile. '.zip');
        $zip->extractTo( storage_path().'/app/'. $tmpFile);
        $zip->close();

        //load to variable
        $pdf = file_get_contents(storage_path().'/app/'. $tmpFile. '/PackageLabels.pdf');

        //remove temp files
        unlink(storage_path().'/app/'. $tmpFile. '.zip');
        unlink(storage_path().'/app/'. $tmpFile. '/PackageLabels.pdf');
        rmdir(storage_path().'/app/'. $tmpFile);

        return $pdf;
    }

    public function importShipments() {
        try {
            $job = ImportFbaShipmentsJob::dispatch();

//            $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
//            $data = [
//                'AWSAccessKeyId' => $this->_accessKey,
//                'Action' => 'ListInboundShipments',
//                'SellerId' => $this->_sellerId,
//                'MWSAuthToken' => $this->_mwsAuthToken,
//                'SignatureVersion' => 2,
//                'Timestamp' => Carbon::now()->format('c'),
//                'Version' => '2010-10-01',
//                'SignatureMethod' => 'HmacSHA256',
//                'LastUpdatedAfter' => Carbon::now()->subHours('24')->format('Y-m-d\TH:i:s'),
//                'LastUpdatedBefore' => Carbon::now()->format('c'),
//                'ShipmentStatusList.member.1' => 'WORKING',
//                'ShipmentStatusList.member.2' => 'SHIPPED',
//                'ShipmentStatusList.member.3' => 'IN_TRANSIT',
//                'ShipmentStatusList.member.4' => 'DELIVERED',
//                'ShipmentStatusList.member.5' => 'CHECKED_IN',
//                'ShipmentStatusList.member.6' => 'RECEIVING',
//                'ShipmentStatusList.member.7' => 'CLOSED',
//                'ShipmentStatusList.member.8' => 'CANCELLED',
//                'ShipmentStatusList.member.9' => 'DELETED',
//                'ShipmentStatusList.member.10' => 'ERROR',
//            ];
//            $response = $this->sendRequest($data);
//            if (!is_array($response)) {
//                $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
//                if (isset($results['ListInboundShipmentsResult']['ShipmentData']['member'])) {
//                    $this->_storeFbaShipments($results['ListInboundShipmentsResult']['ShipmentData']['member']);
//                    $next = $results['ListInboundShipmentsResult']['NextToken'] ?? null;
//
//                    while ($next !== null) {
//                        $data = [
//                            'AWSAccessKeyId' => $this->_accessKey,
//                            'Action' => 'ListInboundShipmentsByNextToken',
//                            'SellerId' => $this->_sellerId,
//                            'MWSAuthToken' => $this->_mwsAuthToken,
//                            'SignatureVersion' => 2,
//                            'Timestamp' => Carbon::now()->format('c'),
//                            'Version' => '2010-10-01',
//                            'SignatureMethod' => 'HmacSHA256',
//                            'NextToken' => $next
//                        ];
//
//                        $response = $this->sendRequest($data);
//                        if (!is_array($response)) {
//                            $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
//                            if (isset($results['ListInboundShipmentsByNextTokenResult']['ShipmentData']['member'])) {
//                                $this->_storeFbaShipments($results['ListInboundShipmentsByNextTokenResult']['ShipmentData']['member']);
//                                $next = $results['ListInboundShipmentsByNextTokenResult']['NextToken'];
//                            } else {
//                                $next = null;
//                            }
//                        }
//                    }
//                }
//                return $this->index();
//            } else {
//                return response()->json('No data found.', 400);
//            }
            return response()->json(compact('job'));
        } catch (\Exception $e) {
            return response()->json($e->getTrace(), 500);
        }
    }

    private function _storeFbaShipments($shipments) {

        if (isset($shipments['ShipmentId'])) {
            $shipment = $this->_createShipment($shipments);
            if (isset($shipment)) {
                $this->fetchShipmentProducts($shipment->getKey(), $shipments['ShipmentId']);
            }
        } else {
            foreach ($shipments as $fbaShipment) {
                $shipment = $this->_createShipment($fbaShipment);

                if (isset($shipment) && isset($fbaShipment['ShipmentId'])) {
                    $this->fetchShipmentProducts($shipment->getKey(), $fbaShipment['ShipmentId']);
                }
            }
        }
    }

    private function _createShipment($shipment) {
        $newEntry = FbaInboundShipment::firstOrNew(['shipment_id' => $shipment['ShipmentId']]);
        $newEntry->destination_fulfillment_center_id = $shipment['DestinationFulfillmentCenterId'];
        $newEntry->label_prep_type = $shipment['LabelPrepType'];

        $warehouse = Warehouse::where('postalCode', $shipment['ShipFromAddress']['PostalCode'])->first();
        $newEntry->ship_from_warehouse_id = $warehouse->id;
        $newEntry->shipment_name = $shipment['ShipmentName'];
        $newEntry->box_content_source = $shipment['BoxContentsSource'] ?? '';
        $newEntry->shipment_status = $shipment['ShipmentStatus'];
        $newEntry->save();

        return $newEntry;
    }

    public function updateFbaShipment( \Illuminate\Http\Request $request, $shipment_id) {

        Log::info("received update request from retoolf for an FBA inbound Shipment");
        $inboundShipment = FbaInboundShipment::where('shipment_id', $shipment_id)->first();

        if(!$inboundShipment) {
            return false;
        }
        Log::info("Shipment found: " . $inboundShipment->shipment_id);

        $data = $request->json()->all();

        $productData = json_decode($data['products']);
        foreach($productData as $product) {
            $products[$product->product_id] = $product->quantity_shipped;
        }


        foreach($inboundShipment->items()->get() as $shipmentItem) {
            if(isset($products[$shipmentItem->product_id]) && $shipmentItem->quantity_shipped != $products[$shipmentItem->product_id]) {
                Log::info("update quantity for ". $shipmentItem->sku . " old: " . $shipmentItem->quantity_shipped . " new: " . $products[$shipmentItem->product_id]);
                $shipmentItem->quantity_shipped = $products[$shipmentItem->product_id];
                $shipmentItem->save();
            }
        }

        //Send updates to amazon
        UpdateFbaInboundShipment::dispatch($inboundShipment);

    }
    public function fetchShipmentProducts($shipment_id, $fba_shipment_id)
    {
        try {
            $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'ListInboundShipmentItems',
                'SellerId' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2010-10-01',
                'SignatureMethod' => 'HmacSHA256',
                'ShipmentId' => $fba_shipment_id,
            ];
            $response = $this->sendRequest($data);

            echo $fba_shipment_id . "\r\n";

            if (!is_array($response)) {
                $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

                if (isset($results['ListInboundShipmentItemsResult']['ItemData']['member'])) {

                    FbaInboundShipmentProduct::where('fba_inbound_shipment_id', '=', $shipment_id)->delete();

                    foreach ($results['ListInboundShipmentItemsResult']['ItemData']['member'] as $product) {
                        if (!isset($product['SellerSKU'])) {
                            $product = $results['ListInboundShipmentItemsResult']['ItemData']['member'];
                        }

                        if (!isset($product['SellerSKU'])) {
                            Log::info("no seller sku? " . json_encode($product));
                            continue;
                        }



                        $localProduct = Product::with('skuMappings')
                            ->where(function ($q) use ($product) {
                                $q->where('sku', '=', $product['SellerSKU']);
                                $q->orWhereHas('skuMappings', function ($sq) use ($product) {
                                    $sq->where('mapping', '=', $product['SellerSKU']);
                                });
                            })->first();

                        if (!$localProduct) {
                            Log::info('product not found in local db? ' . $product['SellerSKU']);
                            continue;
                        }
                        $item = FbaInboundShipmentProduct::firstOrNew(['fba_inbound_shipment_id' => $shipment_id, 'product_id' => $localProduct->id]);
                        $item->sku = $product['SellerSKU'];
                        $item->quantity_shipped = $product['QuantityShipped'];
                        $item->quantity_received = $product['QuantityReceived'];
                        $item->quantity_in_case = $product['QuantityInCase'];
                        $item->release_date = $product['ReleaseDate'] ?? null;
                        $item->fulfillment_network_sku = $product['FulfillmentNetworkSKU'];
                        $item->save();

                        $prepDetails = array_filter($product['PrepDetailsList']);

                        if (!empty($prepDetails)) {
                            $this->storePredInstructions($item->getKey(), $prepDetails);
                        }
                    }
                } else {
                    return false;
                }
            }
        } catch (ClientException $e) {
            Log::info('something went wrong with the FBA Inbound Shipment Products Request ' . json_encode($e->getResponse()->getBody()->getContents()));
        }
    }

    public function storePredInstructions($product_id, $prepDetails)
    {
        FbaInboundShipmentProductPrep::where('fba_is_product_id', '=', $product_id)->delete();
        foreach ($prepDetails as $detail) {
            if (isset($detail['PrepInstruction'])) {
                $fbaIsPp = FbaInboundShipmentProductPrep::firstOrNew(['fba_is_product_id' => $product_id, 'prep_type' => $detail['PrepInstruction'], 'prep_owner' => $detail['PrepOwner']]);
                $fbaIsPp->save();
            } else if (is_array($detail) && !empty($detail)) {
                if (!$this->is_assoc($detail)) {
                    $this->storePredInstructions($product_id, $detail);
                }
            }
        }
    }

    function is_assoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
