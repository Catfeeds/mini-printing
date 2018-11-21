<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'redirect' => 'array'
    ];

    /**
     * @param $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    /**
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return $this->redirect['type'];
    }

    /**
     * @return mixed
     */
    public function getTargetAttribute()
    {
        return $this->redirect['target'];
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpen(Builder $query)
    {
        return $query->where('status', 1);
    }
}
