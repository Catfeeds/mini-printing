<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 18:11
 * Function:
 */

namespace Tanmo\Wechat\Facades;


use Tanmo\Wechat\MiniProgram\Application;
use Tanmo\Wechat\MiniProgram\Contracts\ConfigContract;

class MiniProgram
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

        $configOptions = $config ? config('wechat.mini_program.' . $config) : config('wechat.mini_program.default');

        return new Application($configOptions);
    }
}