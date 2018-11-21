<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
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
            'user_name' => $this->user_name,
            'postal_code' => $this->postal_code,
            'tel' => $this->tel,
            'province' => $this->province,
            'city' => $this->city,
            'county' => $this->county,
            'detail' => $this->detail
        ];
    }
}
