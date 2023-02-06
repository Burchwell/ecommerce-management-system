<?php

namespace App\Console\Commands\Channable\Inventory;

use App\Models\Products\Inventory;
use App\Models\Products\Product;
use App\Models\Products\ProductTurnAround;
use App\Models\Products\Warehouse;
use App\Products\Reports;
use Carbon\Carbon;
use GuzzleHttp\Client;
use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class UpdateJob
 * @package App\Console\Commands\Channable\Inventory
 */
class ChannableUpdateInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:update:inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UpdateJob Inventory based on the Tools CSV feed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $out = new ConsoleOutput();
        $client = new Client();
        $default = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', TRUE);

        $response = $client->request('GET', config('services.tools.csv_feed'));

        // Response Successful
        if ($response->getStatusCode() === 200) {
            $inventory = collect(array_filter(explode("\n", $response->getBody()->getContents())));
            $i=0;
            foreach ($inventory as $stock) {
                if ($i > 0) {
                    $row = explode(',', $stock);
                    if (count($row) === 22) {
                        $map = [
                            "sku" => 0,
                            "vendor" => 1,
                            "tpa_wh_quantity" => 16,
                            "las_wh_quantity" => 17,
                            "total_turn" => 20,
                            "fba_turn" => 19,
                            "las_turn" => 21
                        ];
                    } else {
                        $map = [
                            "sku" => 0,
                            "vendor" => 1,
                            "tpa_wh_quantity" => 15,
                            "las_wh_quantity" => 16,
                            "total_turn" => 19,
                            "fba_turn" => 18,
                            "las_turn" => 20
                        ];
                    }
                    if (!empty($row[$map["sku"]])) {
                        $product = Product::firstOrNew(['qb_sku' => $row[$map["sku"]]], ['qb_sku' => $row[$map["sku"]]]);
                        $product->total_turn = $row[$map['total_turn']];
                        $product->save();

                        echo $stock."\r\n";
                        echo count($row)."\r\n";

                        // Tampa
                        $tpa = Inventory::firstOrNew(['warehouse_id' => 1, 'product_id' => $product->getKey()]);
                        $tpa->quantity = (int)$row[$map['tpa_wh_quantity']];
                        $tpa->save();

                        // Tampa Turn
                        $tpa_turn = ProductTurnAround::firstOrNew(['product_id' => $product->getKey(), 'warehouse_id' => 1]);
                        $tpa_turn->quantity = (int) $row[$map['total_turn']] -  ((int) $row[$map['las_turn']] - (int) $row[$map['fba_turn']]);
                        $tpa_turn->days = 30;
                        $tpa_turn->save();

                        // Vegas
                        $las = Inventory::firstOrNew(['warehouse_id' => 2, 'product_id' => $product->getKey()]);
                        $las->quantity = (int)$row[$map['las_wh_quantity']];
                        $las->save();

                        // Vegas Turn
                        $las_turn = ProductTurnAround::firstOrNew(['product_id' => $product->getKey(), 'warehouse_id' => 2]);
                        $las_turn->quantity = $row[$map['las_turn']];
                        $las_turn->days = 30;
                        $las_turn->save();

                        $fba_wh = Warehouse::where('slug', '=', 'FBA')->first();
                        $fba_turn = ProductTurnAround::firstOrNew(['product_id' => $product->getKey(), 'warehouse_id' => $fba_wh->getKey()]);
                        $fba_turn->quantity = $row[$map['fba_turn']];
                        $fba_turn->days = 30;
                        $fba_turn->save();
                        // Log
                        if ($row[$map["sku"]] === 'SDR-2X12D4') {
                            $out->writeln(__CLASS__ . '-' . date('Y-m-d\TH:i:s') . '-' . $stock);
                        }
                    }
                }
                $i++;
            }
        }
        ini_set('auto_detect_line_endings', $default);
    }
}
