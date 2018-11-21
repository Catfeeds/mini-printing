<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/28
 * Time: 16:09
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;

class OrderRefund extends Model
{
    use Search;
    /**
     * 退款状态:0取消退款 1申请中 2卖家同意 3卖家退款 4退款成功 5卖家拒绝
     */
    const CANCEL = 0;
    const APPLYING = 1;
    const AGREE = 2;
    const REFUNDING = 3;
    const SUCCESS = 4;
    const REFUSE = 5;

    /**
     * @var array
     */
    protected $statusMap = [
        self::CANCEL => 'cancel',
        self::APPLYING => 'applying',
        self::AGREE => 'agree',
        self::REFUNDING => 'refunding',
        self::SUCCESS => 'success',
        self::REFUSE => 'refuse'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
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
    public function getRequirePriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setRequirePriceAttribute($value)
    {
        $this->attributes['require_price'] = $value * 100;
    }

    /**
     * @return mixed
     */
    public function getStatusTextAttribute()
    {
        return $this->statusMap[$this->status];
    }

    /**
     * @param Builder $builder
     * @param $userId
     * @return Builder
     */
    public function scopeFilterUserId(Builder $builder, $userId)
    {
        return $builder->where('user_id', $userId);
    }

    /**
     * @param Builder $builder
     * @param $sn
     * @return Builder
     */
    public function scopeFilterSn(Builder $builder, $sn)
    {
        return $builder->where('sn', $sn);
    }
}