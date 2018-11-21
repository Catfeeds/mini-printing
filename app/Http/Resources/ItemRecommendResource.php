<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemRecommendResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return ItemResource
     */
    public function toArray($request)
    {
        return new ItemResource($this->item);
    }
}
