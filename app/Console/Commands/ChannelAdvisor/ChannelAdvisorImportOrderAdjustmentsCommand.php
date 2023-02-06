<?php

namespace App\Console\Commands\ChannelAdvisor;

use App\Helpers\ChannelAdvisor;
use App\Models\Amazon\SellerCentral;
use App\Models\ChannelAdvisor\AmazonSellerCentral;
use App\Models\ChannelAdvisor\EbayUS;
use App\Models\ChannelAdvisor\Newegg;
use App\Models\Note;
use App\Models\Order;
use App\Models\Orders\Adjustment;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Symfony\Component\Console\Output\ConsoleOutput;

class ChannelAdvisorImportOrderAdjustmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channeladvisor:import:adjustments {--created_at_min=}';

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

        if (!isset($options['created_at_min'])) {
            $options['created_at_min'] = date("Y-m-d\TH:i:s\Z", strtotime(optional(Adjustment::latest('created_at')->first('created_at'))->created_at ?: 'yesterday'));
        }

        if (isset($options['skip'])) {
            $next = $options['skip'];
            unset($options['skip']);
        }

        $adjustments = $ca->getOrderAdjustments($options, $next);

        $this->_processOrderAdjustments($adjustments['value']);
        if (isset($adjustments['@odata.nextLink'])) {

            $next = explode('skip=', $adjustments['@odata.nextLink']);
            $next = $next[count($next) - 1];

            while ($next !== null) {
                $adjustments = $ca->getOrderAdjustments($options, $next);
                $this->_processOrderAdjustments($adjustments['value']);
                if (isset($adjustments['@odata.nextLink'])) {
                    echo $adjustments['@odata.nextLink'] . "\r\n";
                    $next = $this->getSkip($adjustments['@odata.nextLink']);

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
    function _processOrderAdjustments($orders)
    {
        foreach ($orders as $order) {
            $order = $order['Order'];
            // Check of Order Level Adjustments and Store in DB
            $dbOrder = Order::where('order_number', '=', $order['SiteOrderID']);
            if ($dbOrder) {
                if (!empty($order['Adjustments'])) {
                    foreach ($order['Adjustments'] as $adjustment) {
                        $this->_saveOrderAdjustment($dbOrder, Order::class, $adjustment);
                    }
                }

                // Check for Order Item Adjustments and Store in DB
                foreach ($order['Items'] as $item) {
                    if (!empty($item['Adjustments'])) {
                        foreach ($item['Adjustments'] as $adjustment) {
                            if (!empty($adjustment['OrderItemID'])) {
                                $dbItem = OrderItem::where('order_item_id', '=', $adjustment['OrderItemID'])->first();
                                if ($dbItem) {
                                    $this->_saveOrderAdjustment($dbItem, OrderItem::class, $adjustment);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private
    function _saveOrderAdjustment($db, $class, $adjustment) {
        $dbadjustment = Adjustment::firstOrNew([
            'adjustable_type' => $class,
            'adjustable_id' => $db->getKey(),
            'adjustable_order_number' => $db['order_number'],
        ]);

        $dbadjustment->reason = is_numeric($adjustment['Reason']) ? ChannelAdvisor::$adjustmentReasons[$adjustment['Reason']] : $adjustment['Reason'];
        $dbadjustment->is_restock = $adjustment['IsRestock'];
        $dbadjustment->quantity = $adjustment['Quantity'];
        $dbadjustment->type = $adjustment['Type'];
        $dbadjustment->amount = $adjustment['ItemAdjustment'] ?: 0;
        $dbadjustment->tax = $adjustment['TaxAdjustment'] ?: 0;
        $dbadjustment->save();

        if ($adjustment['PublicNotes']) {
            Note::updateOrCreate([
                'notable_id' => $dbadjustment->getKey(),
                'notable_type' => Adjustment::class,
                'subject' => is_numeric($adjustment['Reason']) ? ChannelAdvisor::$adjustmentReasons[$adjustment['Reason']] : $adjustment['Reason'],
                'message' => $adjustment['PublicNotes']
            ]);
        }

        if ($adjustment['Comment']) {
            Note::updateOrCreate([
                'notable_id' => $dbadjustment->getKey(),
                'notable_type' => Adjustment::class,
                'subject' => is_numeric($adjustment['Reason']) ? ChannelAdvisor::$adjustmentReasons[$adjustment['Reason']] : $adjustment['Reason'],
                'message' => $adjustment['Comment']
            ]);
        }
    }
}
