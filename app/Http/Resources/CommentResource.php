<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'nickname' => $this->user->nickname,
            'avatarUrl'=> $this->user->avatarUrl,
            'message' => $this->message,
            'star' => $this->star,
            'images' => CommentImageResource::collection($this->whenLoaded('images')),
            'reply' => optional($this->reply)->message,
            'created_at' => optional($this->created_at)->toDateString()
        ];
    }
}
