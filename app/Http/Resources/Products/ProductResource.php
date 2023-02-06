<?php

namespace App\Http\Resources\Products;

use App\Models\Order;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }


}
