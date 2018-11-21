<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->items->load('covers');
        return [
            'id' => $this->id,
            'sn' => $this->sn,
            'status' => $this->status_text,
            'count' => $this->items->sum('count'),
            'price'=> $this->price,
            'items' => OrderItemResource::collection($this->items)
        ];
    }
}
