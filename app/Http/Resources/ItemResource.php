<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ItemResource extends JsonResource
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
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'status'=>$this->status,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'sales_volume' => $this->sales_volume,
            'stock' => $this->stock,
            'freight'=>$this->freight,
        ];
    }
}
