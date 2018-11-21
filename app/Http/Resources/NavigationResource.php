<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NavigationResource extends JsonResource
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
            'icon' => $this->icon,
            'title' => $this->title,
            'type' => $this->type,
            'target' => $this->target
        ];
    }
}
