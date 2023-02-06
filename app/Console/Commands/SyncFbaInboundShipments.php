<?php

namespace App\Console\Commands;

use App\FbaInboundShipmentProductPrep;
use App\Models\Amazon\FbaInboundShipment;
use App\Models\Amazon\FbaInboundShipmentProduct;
use App\Models\Products\Product;
use App\Models\Products\Warehouse;
use App\Traits\FbaFulfillmentTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;

class SyncFbaInboundShipments extends Command
{
    use FbaFulfillmentTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:fba_shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $output;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->output = new ConsoleOutput();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->writeln('Fetch FBA Orders');
        $this->fetchShipments();
    }


    /**
     * We fetch the Amazon FBA inbound shipments from the past 30 days, and insert new ones / update existing orders
     */
    public function fetchShipments()
    {
        try {
            Log::info('Command: `amazon:fba_shipments` started. - '.str_replace('T', '', Carbon::now()->format('c')));
            $this->loadConfig("/FulfillmentInboundShipment/2020-10-01");
            $data = [
                'AWSAccessKeyId' => $this->_accessKey,
                'Action' => 'ListInboundShipments',
                'SellerId' => $this->_sellerId,
                'MWSAuthToken' => $this->_mwsAuthToken,
                'SignatureVersion' => 2,
                'Timestamp' => Carbon::now()->format('c'),
                'Version' => '2010-10-01',
                'SignatureMethod' => 'HmacSHA256',
                'LastUpdatedAfter' => Carbon::now()->subDays('30')->format('Y-m-d\T00:00:00'),
                'LastUpdatedBefore' => Carbon::now()->format('c'),
                'ShipmentStatusList.member.1' => 'WORKING',
                'ShipmentStatusList.member.2' => 'SHIPPED',
                'ShipmentStatusList.member.3' => 'IN_TRANSIT',
                'ShipmentStatusList.member.4' => 'DELIVERED',
                'ShipmentStatusList.member.5' => 'CHECKED_IN',
                'ShipmentStatusList.member.6' => 'RECEIVING',
                'ShipmentStatusList.member.7' => 'CLOSED',
                'ShipmentStatusList.member.8' => 'CANCELLED',
                'ShipmentStatusList.member.9' => 'DELETED',
                'ShipmentStatusList.member.10' => 'ERROR',
            ];
            $response = $this->sendRequest($data);
            if (!is_array($response)) {
                $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
                if (isset($results['ListInboundShipmentsResult']['ShipmentData']['member'])) {
                    $this->_storeFbaShipments($results['ListInboundShipmentsResult']['ShipmentData']['member']);
                    $next = $results['ListInboundShipmentsResult']['NextToken'] ?? null;

                    while ($next !== null) {
                        $data = [
                            'AWSAccessKeyId' => $this->_accessKey,
                            'Action' => 'ListInboundShipmentsByNextToken',
                            'SellerId' => $this->_sellerId,
                            'MWSAuthToken' => $this->_mwsAuthToken,
                            'SignatureVersion' => 2,
                            'Timestamp' => Carbon::now()->format('c'),
                            'Version' => '2010-10-01',
                            'SignatureMethod' => 'HmacSHA256',
                            'NextToken' => $next
                        ];

                        $response = $this->sendRequest($data);
                        if (!is_array($response)) {
                            $results = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
                            if (isset($results['ListInboundShipmentsByNextTokenResult']['ShipmentData']['member'])) {
                                $this->_storeFbaShipments($results['ListInboundShipmentsByNextTokenResult']['ShipmentData']['member']);
                                $next = $results['ListInboundShipmentsByNextTokenResult']['NextToken'];
                            } else {
                                $next = null;
                                $this->output->writeln("All Shipments fetched.");
                            }
                        }
                    }
                }
            }
            Log::info('Command: `amazon:fba_shipments` completed at. - '.str_replace('T', '', Carbon::now()->format('c')));
        } catch (ClientException $e) {
            Log::info('something went wrong with the FBA Inbound Shipment Request ' . json_encode($e->getResponse()->getBody()->getContents()));
        }
    }

    private function _storeFbaShipments($shipments) {
        foreach ($shipments as $fbaShipment) {
            $shipment = FbaInboundShipment::firstOrNew(['shipment_id' => $fbaShipment['ShipmentId']]);

            $shipmentContent = $this->getShipmentContent($fbaShipment['ShipmentId']);

            if(isset($shipmentContent["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"]["PreviewPickupDate"])) {
                $shipment->scheduled_pickup_at = Carbon::make($shipmentContent["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"]["PreviewPickupDate"])->format('Y-m-d H:i:s');
            }

            if(isset($shipmentContent["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"]["PalletList"]["member"])) {
                $shipment->pallet_count = count($shipmentContent["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"]["PalletList"]["member"]);
            }
            $shipment->destination_fulfillment_center_id = $fbaShipment['DestinationFulfillmentCenterId'];
            $shipment->label_prep_type = $fbaShipment['LabelPrepType'];

            $warehouse = Warehouse::where('postalCode', $fbaShipment['ShipFromAddress']['PostalCode'])->first();
            $shipment->ship_from_warehouse_id = $warehouse->id;
            $shipment->shipment_name = $fbaShipment['ShipmentName'];
            $shipment->box_content_source = isset($fbaShipment['BoxContentsSource']) ? $fbaShipment['BoxContentsSource'] : '';
            $shipment->shipment_status = $fbaShipment['ShipmentStatus'];

            $shipment->save();

            if (isset($shipment) && isset($fbaShipment['ShipmentId'])) {
                $this->fetchShipmentProducts($shipment->getKey(), $fbaShipment['ShipmentId']);
            }
        }
    }

    /**
     */
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
                            Log::info('product not found in local db?? ' . $product['SellerSKU']);
                            Log::info(json_encode($product));
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
                    Log::info('Result member not set.');
                    return false;
                }
            }
        } catch (ClientException $e) {
            Log::info('something went wrong with the FBA Inbound Shipment Products Request ' . json_encode($e->getResponse()->getBody()->getContents()));
        }
        $this->output->writeln("fetched products");
    }

    public function storePredInstructions($product_id, $prepDetails)
    {
        foreach ($prepDetails as $detail) {
            FbaInboundShipmentProductPrep::where('fba_is_product_id', '=', $product_id)->delete();
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
