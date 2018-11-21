<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
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
            'banner' => $this->getBanner(),
            'navigation' => $this->getNavigation(),
            'recommend' => $this->getRecommend()
        ];
    }
}
