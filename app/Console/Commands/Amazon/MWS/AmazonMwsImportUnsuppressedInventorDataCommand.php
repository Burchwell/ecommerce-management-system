<?php

namespace App\Console\Commands\Amazon\MWS;

use App\Models\Amazon\Fulfillment;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use App\Models\Products\ProductMeta;
use App\Traits\FbaFulfillmentTrait;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use JsonException;
use ThiagoMarini\AmazonMwsClient;

/**
 * Class FBAInventorySupply
 * @package App\Console\Commands\Amazon\MWS\FBA
 */
class AmazonMwsImportUnsuppressedInventorDataCommand extends Command
{
    use FbaFulfillmentTrait;

    protected $signature = 'amazon:mws:import:uid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import FBA Orders by date (Default Orders From Day before)';

    public $map = [
        "sku" => 0,
        "asin" => 2,
        "your-price" => 5,
        "mfn-listing-exists" => 6,
        "mfn-fulfillable-quantity" => 7,
        "afn-listing-exists" => 8,
        "afn-warehouse-quantity" => 9,
        "afn-fulfillable-quantity" => 10,
        "afn-unsellable-quantity" => 11,
        "afn-reserved-quantity" => 12,
        "afn-total-quantity" => 13,
        "per-unit-volume" => 14,
        "afn-inbound-working-quantity" => 15,
        "afn-inbound-shipped-quantity" => 16,
        "afn-inbound-receiving-quantity" => 17,
        "afn-researching-quantity" => 18,
        "afn-reserved-future-supply" => 19,
        "afn-future-supply-buyable" => 20
    ];

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        // Get ReportRequest List
        $id = $this->getReportId();

        $report = [];
        $results = $this->getReportById($id);
        $resultRows = explode("\n", $results);
        $i = 0;
        foreach ($resultRows as $row) {
            if ($i > 0) {
                $values = explode("\t", $row);
                $product = Product::where('sku', '=', $values[0])->first();
                if (!empty($product->sku)) {
                    $product->update([
                        'afn_fulfillable_qty' => (int)str_replace("\r", "", $values[10]),
                        'afn_warehouse_qty' => (int)str_replace("\r", "", $values[9]),
                        'afn_reserved_qty' => (int)str_replace("\r", "", $values[12]),
                        'afn_total_qty' => (int)str_replace("\r", "", $values[13]),
                        'afn_per_unit_volume' => (int)str_replace("\r", "", $values[14]),
                        'afn_inbound_working_qty' => (int)str_replace("\r", "", $values[15]),
                        'afn_inbound_shipped_qty' => (int)str_replace("\r", "", $values[16]),
                        'afn_inbound_receiving_qty' => (int)str_replace("\r", "", $values[17])
                    ]);
                    $product->save();
                    $fees = $this->getFeesEstimate($product);

                    if (!is_bool($fees) && $fees !== null) {
                        ProductMeta::updateOrCreate([
                            'product_id' => $product->id,
                            'key' => 'total_fees_estimate',
                            'value' => $fees['TotalFeesEstimate']['Amount'],
                            'value_type' => 'Decimal'
                        ]);

                        $product->total_fees_estimate = $fees['TotalFeesEstimate']['Amount'];
                        $product->save();

                        foreach ($fees['FeeDetailList']['FeeDetail'] as $value) {
                            if (isset($value['FeeType'])) {
                                $key_name = $this->formatString($value['FeeType']);
                                $value = $value['FinalFee']['Amount'];
                                ProductMeta::updateOrCreate([
                                    'product_id' => $product->id,
                                    'key' => $key_name,
                                    'value' => $value,
                                    'value_type' => 'Decimal'
                                ]);
                            }
                        }
                    }
                }
            }
            $i++;
        }
    }

    private function getReportId()
    {
        $response = $this->getReportRequestList('_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_');
        $reports = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        // Get Generated Report ID
        return $reports['GetReportRequestListResult']['ReportRequestInfo'][0]['GeneratedReportId'];
    }

    private function formatString($str)
    {
        return str_replace("f_b_a", 'fba', implode('_', array_map('strtolower', array_filter(preg_split('/(?=[A-Z])/', $str)))));
    }
}
