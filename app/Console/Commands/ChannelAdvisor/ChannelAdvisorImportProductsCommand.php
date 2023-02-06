<?php

namespace App\Console\Commands\ChannelAdvisor;

use App\Helpers\ChannelAdvisor;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use App\Models\Products\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class ChannelAdvisorImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channeladvisor:import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ca = new ChannelAdvisor();
        $out = new ConsoleOutput();

        $filter = Product::$filter;
        $filter[] = ['active', '=', 'Yes'];
        $dbProducts = (Product::where($filter)->get())->toArray();
        foreach ($dbProducts as $product) {
            $caProduct = $ca->getProductBySku($product['sku']);
            if (count($caProduct['value']) > 0) {
                $this->_saveProducts($caProduct);
            }
        }
    }

    private function _saveProducts($products) {
        foreach ($products['value'] as $product) {
            echo "Processing: {$product['Sku']}\r\n";
            $dbProduct = Product::where('sku', '=', $product['Sku'])->first();
            if (!empty($dbProduct)) {
                $dbProduct->channel_advisor_id = $product['ID'];
                $dbProduct->cost = $product['Cost'];
                $dbProduct->price = $product['StorePrice'];
                if (count($product['DCQuantities']) > 0) {
                    try {
                        Inventory::where([['product_id', '=', $dbProduct->getKey()], ["warehouse_id", '=', 3]])->update(["quantity" => $product['DCQuantities'][0]['AvailableQuantity']]);
                        echo "Processed: {$product['Sku']} -- QTY: {$product['DCQuantities'][0]['AvailableQuantity']}\r\n";
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        Log::info('SKU: '.$dbProduct->sku." error. ".$e->getMessage());
                    }
                }
                $dbProduct->save();
            }
        }

    }
}
