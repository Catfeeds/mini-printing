<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'national_code' => $this->national_code,
            'postal_code' => $this->postal_code,
            'tel' => $this->tel,
            'province'=> $this->province,
            'city' => $this->city,
            'county' => $this->county,
            'detail' => $this->detail,
            'user_name' => $this->user_name,
        ];
    }
}