<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'avatarUrl' => $this->avatarUrl,
            'nickname' => $this->nickname,
            'gender' => $this->gender,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'birthday' => substr($this->birthday,0,10),
            'phone' => $this->phone,
        ];
    }
}
