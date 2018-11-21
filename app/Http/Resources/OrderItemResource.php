<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'item_id' => $this->item_id,
            'title' => $this->item->title,
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'price' => $this->price,
            'count' => $this->count
        ];
    }
}
