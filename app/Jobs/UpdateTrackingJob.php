<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filters = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::whereNotNull('shipment_tracking_number')
            ->leftJoin('labels', function ($join) {
                $join->on('labels.order_number', '=', 'orders.order_number');
            });

        if (!empty($this->filters)) {
            $order->where($this->filter)
                ->whereNotIn('shipment_tracking_last_event', ['Delivered', 'delivered']);
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
