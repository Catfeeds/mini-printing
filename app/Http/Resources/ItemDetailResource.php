<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailResource extends JsonResource
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
            'title' => $this->title,
            'covers' => CoverResource::collection($this->whenLoaded('covers')),
            'price' => $this->price,
            'original_price' => $this->original_price,
            'freight' => $this->freight,
            'sales_volume' => $this->sales_volume,
            'stock' => $this->stock,
            'comments' => $this->comments->count(),
            'details' => $this->details
        ];
    }
}
