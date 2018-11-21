<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'express' => $this->express,
            'address' => new OrderAddressResource($this->address),
            'items' => OrderItemResource::collection($this->items),
            'freight' => $this->freight,
            'price' => $this->price,
            'status' => $this->status_text,
            'sn' => $this->sn,
            'wechat_sn' => optional($this->wechatPayment)->sn,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'paid_at' => $this->paid_at,
            'delivered_at' => $this->delivered_at,
            'confirmed_at' => $this->confirmed_at,
        ];
    }
}
