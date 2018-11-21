<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRefundDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->order->items->load('covers');
        return [
            'status' => $this->status_text,
            'require_price' => $this->require_price,
            'price' => $this->price,
            'reason' => $this->reason,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'sn' => $this->sn,
            'items' => OrderItemResource::collection($this->order->items)
        ];
    }
}
