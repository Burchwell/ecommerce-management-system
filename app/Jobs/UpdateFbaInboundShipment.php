<?php

namespace App\Jobs;

use App\Models\Amazon\FbaInboundShipment;
use App\Traits\FbaFulfillmentTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFbaInboundShipment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FbaFulfillmentTrait;

    private $_shipment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FbaInboundShipment $shipment)
    {
        $this->_shipment = $shipment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("post data to amazon");
        //Connect to amazon and update the inbound shipment
        $res = $this->updateShipment($this->_shipment);
        \Log::info(json_encode($res));
    }

}
