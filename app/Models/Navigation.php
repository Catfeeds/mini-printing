<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/26
 * Time: 10:31
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Navigation extends Model
{
    /**
     * @param $value
     * @return string
     */
    public function getIconAttribute($value)
    {
        return Storage::disk('public')->url($value);
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