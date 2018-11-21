<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tanmo\Search\Traits\Search;

class Item extends Model
{
    use Search, SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function covers()
    {
        return $this->hasMany(ItemCover::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recommend()
    {
        return $this->hasOne(ItemRecommend::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getPriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getOriginalPriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setOriginalPriceAttribute($value)
    {
        $this->attributes['original_price'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getFreightAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setFreightAttribute($value)
    {
        $this->attributes['freight'] = $value * 100;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWarning(Builder $builder)
    {
        return $builder->where('stock', '<=', config('admin.early_warning'));
    }

    /**
     * @param Builder $builder
     * @param string $order
     * @return Builder
     */
    public function scopeWithOrder(Builder $builder, $order = '')
    {
        /// 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'sales_desc':
                $builder->orderBy('sales_volume', 'desc');
                break;
            case 'sales_asc':
                $builder->orderBy('sales_volume', 'asc');
                break;
            case 'price_asc':
                $builder->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $builder->orderBy('price', 'desc');
                break;
            default:
                $builder->orderBy('created_at', 'desc');
                break;
        }

        return $builder;
    }
}
