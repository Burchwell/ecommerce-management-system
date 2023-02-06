<?php

namespace App\Http\Resources\Labels;


use App\Helpers\ResourceCollection;

/**
 * Class LabelResourceCollection
 * @package App\Http\Resources
 */
class LabelResourceCollection extends ResourceCollection
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
