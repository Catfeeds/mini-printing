<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCover extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
