<?php

namespace App\Console\Commands\Shopify;

use App\Helpers\FedEx;
use App\Helpers\Helpers;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Models\Shopify\Order\Google;
use App\Models\Shopify\Order\Walmart;
use App\Models\Shopify\Order\Web;
use App\Models\Shopify\Order\Wholesale;
use App\Traits\Tracking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class importProducts
 * @package App\Console\Commands\Shopify
 */
class ImportOrders extends Command
{
    use Tracking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:import:orders {--status=} {--since_id=} {--created_at_min=} {--created_at_max=} {--updated_at_min=} {--updated_at_max=} {--limit=250} {--fulfillment_status=shipped}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $shopify;

    private $source = [
        'walmart' => Walmart::class,
        'web' => Web::class,
        '2404230' => Google::class,
        '1150484'=> Wholesale::class
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $config = [
            "ShopUrl" => config('shopify.store'),
            "ApiKey" => config('shopify.api_key'),
            "SharedSecret" => config('shopify.shared_secret')
        ];

        $this->shopify = new ShopifySDK($config);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $out = new ConsoleOutput();
        $options = array_filter($this->options());
        if (isset($options['updated_at_min'])) {
            $options['updated_at_min'] = $this->formatDate($options['updated_at_min']);
        }

        if (isset($options['updated_at_max'])) {
            $options['updated_at_max'] = $this->formatDate($options['updated_at_max']);
        }

        if (isset($options['created_at_min'])) {
            $options['created_at_min'] = $this->formatDate($options['created_at_min']);
        }

        if (isset($options['created_at_max'])) {
            $options['created_at_max'] = $this->formatDate($options['created_at_max']);
        }

        $orders = $this->shopify->Order->get($options);
        $options['since_id'] = $this->_saveOrders($orders);

        if (isset($options['updated_at_min'])) {
            unset($options['updated_at_min']);
        }

        while ($orders = $this->shopify->Order->get($options)) {
            $options['since_id'] = $this->_saveOrders($orders);
        }
    }

    private function _saveOrders($orders)
    {
        echo $orders[0]['updated_at'] . "\r\n";
        foreach ($orders as $order) {
            if ($order['source_name'] !== 'shopify_draft_order') {
                $nworder = Order::where('order_number', '=', str_replace('#', '', $order['name']))->first();
                if (!empty($nworder)) {
                    $nworder->order_id = $order['id'];
                    $nworder->source = $this->source[$order['source_name']];
                    if (isset($order['shipping_address'])) {
                        $nworder->city = $order['shipping_address']['city'];
                        $nworder->state = $order['shipping_address']['province_code'];
                        $nworder->zipcode = $order['shipping_address']['zip'];
                    }
                    $nworder->status = $order['fulfillments'][0]['status'] === "success" ? 'shipped' : $order['fulfillments'][0]['status'];
                    $nworder->purchased_at = Carbon::parse($order['processed_at'])->format('Y-m-d h:i:s');
                    $nworder->shipped_at = Carbon::parse($order['fulfillments'][0]['created_at'])->format('Y-m-d h:i:s');
                    $nworder->created_at = Carbon::parse($order['created_at'])->format('Y-m-d h:i:s');
                    $nworder->updated_at = Carbon::parse($order['updated_at'])->format('Y-m-d h:i:s');

//                if (isset($order['fulfillments'][0]['tracking_number'])) {
//
//                    $trackingInfo = $this->_getTrackingUpdates($order['fulfillments'][0]['tracking_number']);
//                    if ($trackingInfo !== null ) {
//                        $nworder->shipment_carrier = $trackingInfo['shipment_carrier'];
//                        $nworder->shipment_tracking_number = $order['fulfillments'][0]['tracking_number'];
//
//                        if (isset($trackingInfo['shipment_code'])) {
//                            $nworder->shipment_code = $trackingInfo['shipment_code'];
//                        }
//
//                        if (isset($trackingInfo['shipment_package_type'])) {
//                            $nworder->shipment_package_type = $trackingInfo['shipment_package_type'];
//                        }
//                    }
//                }

                    $nworder->save();

                    foreach ($order['line_items'] as $item) {
                        $nwitem = [
                            'sku' => $item['sku'],
                            'order_id' => $nworder->getKey(),
                            'order_item_id' => $item['id'],
                            'order_number' => $order['number'],
                            'quantity' => $item['quantity']
                        ];
                        $oi = OrderItem::updateOrCreate($nwitem, $nwitem);
                    }
                }
            }
        }
        return $orders[count($orders) - 1]['id'];
    }

    private function formatDate($date)
    {
        return Carbon::parse($date, 'America/New_York')->format('c');
    }
}
