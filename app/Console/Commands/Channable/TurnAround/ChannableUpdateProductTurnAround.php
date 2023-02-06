<?php

namespace App\Console\Commands\Channable\TurnAround;

use App\Models\ChannelAdvisor\AmazonSellerCentral;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChannableUpdateProductTurnAround extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channable:update:product:turnaround';

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
        $fba_orders = OrderItem::select(['sku', DB::raw('SUM(quantity) as qty'), 'source'])
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'order_items.order_id')->where('source', '=', AmazonSellerCentral::class)->select('source');
            })
            ->where(DB::raw('orders.created_at BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()'))
            ->groupBy(['source', 'sku'])
            ->orderBy('sku', 'ASC')
            ->get();

        foreach ($fba_orders as $ta) {
            echo json_encode($ta);
            $filters = Product::$filter + ['sku', '=', $ta['sku']];
            $product = Product::where($filters)->first();
            $updated = Inventory::updateOrCreate(['product_id' => $product->getKey(), 'warehouse_id' => 3], ['product_id' => $product->getKey(), 'warehouse_id' => 3, 'quantity' => $ta->qty]);
            print_r(['product_id' => $product->getKey(), 'warehouse_id' => 3, 'quantity' => round($ta->qty/30)]);
            echo "\r\n";
            echo $ta->qty."\r\n";
            echo $updated."\r\n";
        }
    }
}
