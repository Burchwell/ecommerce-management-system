<?php

namespace App\Console\Commands\Tracking;

use App\Models\Order;
use App\Traits\Tracking;
use Illuminate\Console\Command;

class UpdateTrackingInfo extends Command
{
    use Tracking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:tracking:update {--orderNumber=} {--created_at_min=} {--created_at_max=} {--printed_at_start=} {--printed_at_end=} {--exclude_last_event=delivered}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \JsonException
     */
    public function handle()
    {
        $options = array_filter($this->options());
        $filter = [];
        $filter[] = [\DB::raw('LOWER(status)'), '!=', 'canceled'];
        $filter[] = [\DB::raw('LOWER(shipment_tracking_last_event)'), '!=', 'delivered'];

        if (array_key_exists('orderNumber', $options)) {
            $filter[] = ['order_number', '=', $options['orderNumber']];
        } else {
            if (array_key_exists('created_at_min', $options)) {
                $filter[] = ['created_at', '>', date("Y-m-d\TH:i:s\Z", strtotime($options['created_at_min']))];
            }

            if (array_key_exists('created_at_max', $options)) {
                $filter[] = ['created_at', '<', date("Y-m-d\TH:i:s\Z", strtotime($options['created_at_max']))];
            }

            if (array_key_exists('printed_at_min', $options)) {
                $filter[] = ['printed_at', '>', date("Y-m-d\TH:i:s\Z", strtotime($options['printed_at_min']))];
            }

            if (array_key_exists('printed_at_max', $options)) {
                $filter[] = ['printed_at', '<', date("Y-m-d\TH:i:s\Z", strtotime($options['printed_at_max']))];
            }

            if (array_key_exists('shipment_updated_at_min', $options)) {
                $filter[] = ['shipment_tracking_last_upddated_api_at', '>', date("Y-m-d\TH:i:s\Z", strtotime($options['shipment_updated_at_min']))];
            }
        }

        $order = Order::whereNotNull('shipment_tracking_number')
            ->leftJoin('labels', function ($join) {
                $join->on('labels.order_number', '=', 'orders.order_number');
            });

        if (!empty($filter)) {
            $order->where($filter);
            if (array_key_exists('exclude_last_event', $options)) {
                $order->whereNotIn('shipment_tracking_last_event', ['Delivered', 'delivered']);
            }
        }
        $order->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                \Log::info("Trying to update tracking for Order # {$order->order_number}.");
                $updates = $this->_getTrackingUpdates($order->shipment_tracking_number);
                if ($updates !== null) {
                    $order->update($updates);
                    $order->save();
                    \Log::info("Tracking for Order # {$order->order_number} updated.");
                } else {
                    \Log::info("No shipping info for Order # {$order->order_number} found.");
                }
            }
        });
    }
}
