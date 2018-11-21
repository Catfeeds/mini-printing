<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuthWechat extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_auth_wechat';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'open_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $openId
     * @return Model|null|object|static
     */
    public function getByOpenId($openId)
    {
        return $this->where('open_id', $openId)->first();
    }
}
