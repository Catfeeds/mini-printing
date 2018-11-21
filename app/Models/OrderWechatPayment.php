<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 17:21
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderWechatPayment extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['sn'];
}