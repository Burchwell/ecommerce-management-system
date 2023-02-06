<?php

namespace App\Console\Commands\Amazon\MWS;

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
class AmazonMwsImportReservedInventoryDataCommand extends Command
{
    use FbaFulfillmentTrait;

    protected $signature = 'amazon:mws:import:rid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import FBA Orders by date (Default Orders From Day before)';

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        // Get ReportRequest List
        $id = $this->getReportId();
        $results = $this->getReportById($id);
        $resultRows = explode("\n", $results);
        $i=0;
        foreach ($resultRows as $row) {
            if ($i >= 0) {
                $values = explode("\t", $row);
                Product::where('sku', '=', $values[0])->update([
                    'fba_reserved_qty' => (int) str_replace("\r", "", $values[4]),
                    'fba_reserved_customer_orders' => (int) str_replace("\r", "", $values[5]),
                    'fba_reserved_fc_transfers' => (int) str_replace("\r", "", $values[6]),
                    'fba_reserved_fc_processing' => (int) str_replace("\r", "", $values[7])
                ]);
            }
            $i++;
        }
    }

    private function getReportId() {
        $response = $this->getReportRequestList('_GET_RESERVED_INVENTORY_DATA_');
        $reports = json_decode(json_encode(simplexml_load_string($response), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        // Get Generated Report ID
        return $reports['GetReportRequestListResult']['ReportRequestInfo'][0]['GeneratedReportId'];
    }

}
