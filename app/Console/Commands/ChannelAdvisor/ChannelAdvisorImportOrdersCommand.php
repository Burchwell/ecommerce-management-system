<?php

namespace App\Console\Commands\ChannelAdvisor;

use App\Helpers\ChannelAdvisor;
use App\Helpers\Tracking\FedEx;
use App\Helpers\Helpers;
use App\Models\Amazon\SellerCentral;
use App\Models\ChannelAdvisor\AmazonSellerCentral;
use App\Models\ChannelAdvisor\EbayUS;
use App\Models\ChannelAdvisor\Newegg;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use App\Traits\Tracking;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\TextUI\Help;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Symfony\Component\Console\Output\ConsoleOutput;

class ChannelAdvisorImportOrdersCommand extends Command
{
    use Tracking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channeladvisor:import:orders {--created_at_min=} {--created_at_max=} {--DistributionCenterTypeRollup=} {--SiteOrderID=} {--skip=} {--PaymentStatus=} {--ShippingStatus=} {--CheckoutStatus=}';

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
    public
    function handle()
    {
        $ca = new ChannelAdvisor();
        $out = new ConsoleOutput();

        $options = array_filter($this->options());
        $next = 0;

        if (
            !isset($options['SiteOrderID']) &&
            !isset($options['DistributionCenterTypeRollup']) &&
            !isset($options['RequestedShippingCarrier']) &&
            !isset($options['created_at_min'])) {
            $options['created_at_min'] = date("Y-m-d\TH:i:s\Z", strtotime(optional(Order::whereIn('source', [
                EbayUS::class,
                AmazonSellerCentral::class,
                Newegg::class,

            ])->latest('created_at')->first('created_at'))->created_at ?? 'yesterday'));
        }

        if (isset($options['skip'])) {
            $next = $options['skip'];
            unset($options['skip']);
        }

        $orders = $ca->getOrders($options, $next);
        $this->_saveOrders($orders['value']);
        if (isset($orders['@odata.nextLink'])) {
            $next = explode('skip=', $orders['@odata.nextLink']);
            $next = $next[count($next) - 1];

            while ($next !== null) {
                $orders = $ca->getOrders($options, $next);
                $this->_saveOrders($orders['value']);

                if (isset($orders['@odata.nextLink'])) {
//                    echo $orders['@odata.nextLink'] . "\r\n";
                    $next = $this->getSkip($orders['@odata.nextLink']);
                } else {
                    $next = null;
                }
            }
        }
    }

    private
    function getSkip($next)
    {
        $nextArr = explode('skip=', $next);
        return $nextArr[count($nextArr) - 1];
    }

    private
    function _saveOrders($orders)
    {

        foreach ($orders as $order) {
            if (isset($order['ID'])) {
                //$tracking = $this->_getNovaData($order['SiteOrderID']);
                $nworder = Order::where(['order_number' => $order['SiteOrderID']])->first();
                if (!empty($nworder)) {
                    $nworder->source = ChannelAdvisor::$site[$order['SiteName']];
                    $nworder->order_id = $order['ID'];
                    if ($order['DistributionCenterTypeRollup'] === 'ExternallyManaged') {
                        $nworder->fulfillment_by = 'AMAZN_FBA';
                    } else {
                        $nworder->fulfillment_by = $order['RequestedShippingCarrier'];
                    }

                    $nworder->status = $order['ShippingStatus'];
                    $nworder->city = $order['ShippingCity'];
                    $nworder->state = $order['ShippingStateOrProvince'];
                    $nworder->zipcode = $order['ShippingPostalCode'];
                    $nworder->purchased_at = Carbon::parse($order['CreatedDateUtc'], 'America/New_York')->format('Y-m-d h:i:s');
                    $order['ShippingPostalCode'];
                    $nworder->created_at = $order['CreatedDateUtc'] ? Carbon::parse($order['CreatedDateUtc'], 'America/New_York')->format('Y-m-d h:i:s') : null;
                    $nworder->updated_at = $order['ShippingDateUtc'] ? Carbon::parse($this->formatDT($order['ShippingDateUtc']), 'America/New_York')
                        ->format('Y-m-d h:i:s') : Carbon::parse($this->formatDT($order['PaymentDateUtc']), 'America/New_York')
                        ->format('Y-m-d h:i:s');


//                if (isset($order['Fulfillments'][0]['TrackingNumber'])) {
//                    $trackingInfo = $this->_getTrackingUpdates($order['Fulfillments'][0]['TrackingNumber']);
//                    $nworder->shipment_carrier = $trackingInfo['shipment_carrier'];
//                    $nworder->shipment_code = $trackingInfo['shipment_code'];
//                    $nworder->shipment_package_type = $trackingInfo['shipment_carrier'];
//                    $nworder->shipment_tracking_number = $order['Fulfillments'][0]['TrackingNumber'];
//                }
                    $nworder->save();

                    $this->_saveOrderItems($nworder->getKey(), $order['SiteOrderID'], $order['Items']);
                }
            }
        }
    }

    private
    function _saveOrderItems($order_id, $order_number, $items)
    {
        foreach ($items as $item) {
            $itm = ["order_number" => $order_number, "sku" => $item['Sku']];
            $nwoi = OrderItem::firstOrNew($itm);
            $nwoi->order_id = $order_id;
            $nwoi->order_item_id = $item['ID'];
            $nwoi->sku = $item['Sku'];
            $nwoi->quantity = $item['Quantity'];
            $nwoi->save();
        }
    }

    private
    function formatDT($dateTime, $sourceTZ = 'UTC', $targetTZ = 'America/New_York')
    {
        $dt = new DateTime($dateTime, new DateTimeZone($sourceTZ));
        return ($dt->setTimezone(new DateTimeZone($targetTZ)))->format('Y-m-d\TH:i:s\Z');
    }
}
