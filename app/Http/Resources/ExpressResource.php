<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $info = $this->getTrackingInfo();
        $item = $this->items->first();

        return [
            'name' => $info['name'] ?? '',
            'type' => $info['type'] ?? '',
            'site' => $info['site'] ?? '',
            'phone' => $info['phone'] ?? '',
            'no' => $info['no'],
            'state' => $info['state'],
            'list' => $info['list'],
            'address' => new OrderAddressResource($this->address),
            'cover' => new CoverResource($item->covers->first())
        ];
    }
}
