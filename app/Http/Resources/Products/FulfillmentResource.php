<?php

namespace App\Http\Resources\Products;

use App\Models\Order;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FulfillmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'cost' => $this->cost,
            'total_fees_estimate' => $this->total_fees_estimate,
            'total_profit_estimate' => $this->total_profit_estimate,
            'quantity_on_hand_tampa' => $this->quantity_on_hand_tampa ?: 0,
            'quantity_on_hand_las_vegas' => $this->quantity_on_hand_las_vegas ?: 0,
            'afn_fulfillable_qty' => $this->afn_fulfillable_qty ?: 0,
            'afn_inbound_shipped_qty' => $this->afn_inbound_shipped_qty ?: 0,
            'fba_reserved_fc_processing' => $this->fba_reserved_fc_processing ?: 0,
            'fba_reserved_customer_orders' => $this->fba_reserved_customer_orders ?: 0,
            'turn_around_time_fba' => $this->turn_around_time_fba ?: 0,
            'turn_around_time_tampa' => $this->total_turn - ($this->turn_around_time_fba + $this->turn_around_time_las_vegas),
            'turn_around_time_las_vegas' => $this->turn_around_time_las_vegas ?: 0,
            'turn_around_time_total' => $this->total_turn ?: 0,
        ];
    }


}
