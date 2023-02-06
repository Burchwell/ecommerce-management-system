<?php

namespace App\Http\Resources\Labels;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LabelResource
 * @package App\Http\Resources\Labels
 */
class LabelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'pdf' => $this->pdf,
            'printed_at' => $this->printed_at,
            'computer_name' => $this->computer_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
