<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 16:16
 * Function:
 */

namespace App\Models;


use App\Exceptions\LowStocksException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Tanmo\Search\Traits\Search;

class Order extends Model
{
    use Search, SoftDeletes;

    /**
     * 订单状态:0待付款 1待发货 2待收货 3待评价 4已完成
     */
    const WAIT_PAY = 0;
    const WAIT_DELIVER = 1;
    const WAIT_RECV = 2;
    const WAIT_COMMENT = 3;
    const FINISH = 4;
    const REFUND = 5;

    /**
     * status map
     *
     * @var array
     */
    protected $statusMap = [
        self::WAIT_PAY => 'wait_pay',
        self::WAIT_DELIVER => 'wait_deliver',
        self::WAIT_RECV => 'wait_recv',
        self::WAIT_COMMENT => 'wait_comment',
        self::FINISH => 'finish',
        self::REFUND => 'refund'
    ];

    /**
     * @var array
     */
    protected $fillable = ['user_id' , 'sn', 'price', 'freight', 'payable_price', 'items_price', 'remark'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(OrderAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wechatPayment()
    {
        return $this->hasOne(OrderWechatPayment::class);
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
    public function getPriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setPayablePriceAttribute($value)
    {
        $this->attributes['payable_price'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getPayablePriceAttribute($value)
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
     * @param $value
     * @return float|int
     */
    public function getFreightAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @return mixed
     */
    public function getStatusTextAttribute()
    {
        if (empty($this->status)) {
            return $this->statusMap[self::WAIT_PAY];
        }

        return $this->statusMap[$this->status];
    }

    /**
     * @return string
     */
    public function getExpressAttribute()
    {
        $info = $this->getTrackingInfo();

        if (!empty($info['list'])) {
            return $info['list'][0]['content'];
        }

        return '暂无物流信息';
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
     * @param $status
     * @return Builder
     */
    public function scopeFilterStatus(Builder $builder, $status)
    {
        if (array_key_exists($status, $this->statusMap)) {
            return $builder->where('status', $status)->where('refund', 0);
        }

        $value = array_flip($this->statusMap)[$status];
        return $builder->where('status', $value)->where('refund', 0);
    }

    /**
     * @param Builder $builder
     * @param int $refund
     * @return Builder
     */
    public function scopeFilterRefund(Builder $builder, $refund = 1)
    {
        return $builder->where('refund', $refund);
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

    //-----------------------------------------------------------------------//

    /**
     * @param User $user
     * @param array $items
     * @param array $address
     * @param null $remark
     * @return mixed
     */
    public function submit(User $user, array $items, array $address, $remark = null)
    {
        return DB::transaction(function () use ($user, $items, $address, $remark) {
            $order = $this->create([
                'user_id' => $user->id,
                'sn' => date('YmdHis') . $user->id . rand(10, 99),
                'price' => 0,
                'payable_price' => 0,
                'items_price' => 0,
                'freight' => 0,
                'remark' => $remark
            ]);

            $orderAddress = new OrderAddress($address);
            $order->address()->save($orderAddress);

            foreach ($items as $item) {
                $itemInfo = Item::where('id', $item['id'])->lockForUpdate()->first();

                if ($itemInfo && ($itemInfo->stock >= $item['count'])) {
                    /// 减库存
                    $itemInfo->stock -= $item['count'];
                    $itemInfo->sales_volume += $item['count'];
                    $itemInfo->save();

                    /// 写入数据库
                    $orderItem = new OrderItem(['item_id' => $item['id'], 'count' => $item['count'], 'price' => $itemInfo->price]);
                    $order->items()->save($orderItem);

                    $freight = $itemInfo->freight * $item['count'];
                    $price = $itemInfo->price;

                    ///
                    $order->price += $price * $item['count'] + $freight;
                    $order->payable_price += $price * $item['count'] + $freight;
                    $order->items_price += $price * $item['count'];
                    $order->freight += $freight;
                }
                else {
                    throw new LowStocksException($item['id']);
                }
            }

            $order->save();

            return $order;
        });
    }

    /**
     * @return mixed
     */
    public function getTrackingInfo()
    {
        $query['no'] = $this->tracking_no;
        if (!empty($this->express_type) && is_string($this->express_type)) {
            $query['type'] = $this->express_type;
        }

        $data = Curl::to(config('express.api'))
            ->withHeader("Authorization:APPCODE " . config('express.app_code'))
            ->withData($query)
            ->get();

        return json_decode($data, true);
    }
    
}