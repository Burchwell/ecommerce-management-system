<?php

namespace App\Console\Commands\Amazon\MWS;

use App\FbaRestockInventoryRecommendations;
use App\Models\Amazon\Fulfillment;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
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
class AmazonMwsImportRestockinventoryRecommendationsReportCommand extends Command
{
    use FbaFulfillmentTrait;

    protected $signature = 'amazon:mws:import:report:restock';

    private $map = [
        "country" => 0,
        "product_name" => 1,
        "fnsku" => 2,
        "merchant_sku" => 3,
        "asin" => 4,
        "condition" => 5,
        "supplier" => 6,
        "supplier_part_no" => 7,
        "currency_code" => 8,
        "price" => 9,
        "sales_last_30_days" => 10,
        "units_sold_last_30_days" => 11,
        "total_units" => 12,
        "inbound" => 13,
        "available" => 14,
        "fc_transfer" => 15,
        "fc_processing" => 16,
        "customer_order" => 17,
        "unfulfillable" => 18,
        "fulfilled_by" => 19,
        "days_of_supply" => 20,
        "alert" => 21,
        "recommended_replenishment_qty" => 22,
        "recommended_ship_date" => 23,
        "inventory_level_threshold's_published_–_current_month" => 24,
        "current_month_-_very_low_inventory_threshold" => 25,
        "current_month_-_minimum_inventory_threshold" => 26,
        "current_month_-_maximum_inventory_threshold" => 27,
        "current_month_-_very_high_inventory_threshold" => 28,
        "inventory_level_threshold's_published_–_next_month" => 29,
        "next_month_-_very_low_inventory_threshold" => 30,
        "next_month_-_minimum_inventory_threshold" => 31,
        "next_month_-_maximum_inventory_threshold" => 32,
        "next_month_-_very_high_inventory_threshold" => 33,
        "utilization" => 34,
        "maximum_shipment_quantity" => 35,
    ];
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import FBA Restock Inventory Recommendations Report';

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        // Request Report
        $this->requestReport('_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_');

        // Get Report List
        $id = $this->getReportId();

        $results = $this->getReportById($id);
        $resultRows = explode("\n", $results);
        $i = 0;
        foreach ($resultRows as $row) {
            if ($i >= 1) {
                $values = explode("\t", $row);
                $product = Product::with('skuMappings')
                    ->where(function ($q) use ($values) {
                        $q->where('sku', '=', $values[$this->map['merchant_sku']]);
                        $q->orWhereHas('skuMappings', function ($sq) use ($values) {
                            $sq->where('mapping', '=', $values[$this->map['merchant_sku']]);
                        });
                    })->first();

                if (isset($product)) {
                    $restock = FbaRestockInventoryRecommendations::firstOrNew(['product_id' => $product->getKey()]);
                    $restock->sales_last_thirty = $values[$this->map['sales_last_30_days']];
                    $restock->units_sold_thirty = $values[$this->map['units_sold_last_30_days']];
                    $restock->total_units = $values[$this->map['total_units']];
                    $restock->inbound = $values[$this->map['inbound']];
                    $restock->available = $values[$this->map['available']];
                    $restock->fc_transfer = $values[$this->map['fc_transfer']];
                    $restock->fc_processing = $values[$this->map['fc_processing']];
                    $restock->customer_order = $values[$this->map['customer_order']];
                    $restock->unfulfillable = $values[$this->map['unfulfillable']];
                    $restock->days_of_supply = $values[$this->map['days_of_supply']];
                    $restock->alert = $values[$this->map['alert']];
                    $restock->recommended_replenishment_qty = $values[$this->map['recommended_replenishment_qty']];
                    $restock->recommended_ship_date = $values[$this->map['recommended_ship_date']];
                    $restock->current_month_inventory_level_thresholds_published = $values[$this->map['inventory_level_threshold\'s_published_–_current_month']];
                    $restock->current_month_very_low_inventory_threshold = $values[$this->map['current_month_-_very_low_inventory_threshold']];
                    $restock->current_month_minimum_inventory_threshold = $values[$this->map['current_month_-_minimum_inventory_threshold']];
                    $restock->current_month_maximum_inventory_threshold = $values[$this->map['current_month_-_maximum_inventory_threshold']];
                    $restock->current_month_very_high_inventory_threshold = $values[$this->map['current_month_-_very_high_inventory_threshold']];
                    $restock->next_month_inventory_level_thresholds_published = $values[$this->map['inventory_level_threshold\'s_published_–_next_month']];
                    $restock->next_month_very_low_inventory_threshold = $values[$this->map['next_month_-_very_low_inventory_threshold']];
                    $restock->next_month_minimum_inventory_threshold = $values[$this->map['next_month_-_minimum_inventory_threshold']];
                    $restock->next_month_maximum_inventory_threshold = $values[$this->map['next_month_-_maximum_inventory_threshold']];
                    $restock->next_month_very_high_inventory_threshold = $values[$this->map['next_month_-_very_high_inventory_threshold']];
                    $restock->utilization = $values[$this->map['utilization']];
                    $restock->maximum_shipment_quantity = $values[$this->map['maximum_shipment_quantity']];
                    $restock->save();
                } else {
                    Log::alert("Cant find product for row ". $values[$this->map['merchant_sku']]);
                }
            }
            $i++;
        }
    }

    private function getReportId()
    {
        $response = $this->getReportRequestList('_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_');
        $reports = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        // Get Generated Report ID
        return $reports['GetReportRequestListResult']['ReportRequestInfo'][1]['GeneratedReportId'];
    }

}
