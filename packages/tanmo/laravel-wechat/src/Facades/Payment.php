<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 10:39
 * Function:
 */

namespace Tanmo\Wechat\Facades;


use Tanmo\Wechat\Payment\Application;
use Tanmo\Wechat\Payment\Contracts\ConfigContract;

class Payment
{
    /**
     * @param null $config
     * @return Application
     */
    public static function app($config = null)
    {
        if ($config instanceof ConfigContract) {
            return new Application($config);
        }

        $configOptions = $config ? config('wechat.payment.' . $config) : config('wechat.payment.default');

        return new Application($configOptions);
    }
}