<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CommentImage extends Model
{
    /**
     * @return mixed
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
