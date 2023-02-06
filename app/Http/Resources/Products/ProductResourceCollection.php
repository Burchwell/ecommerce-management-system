<?php

namespace App\Http\Resources\Products;

use App\Helpers\ResourceCollection;

/**
 * Class FulfillmentResourceCollection
 * @package App\Http\Resources\Products
 */
class ProductResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->pagination
        ];
    }


}
