<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 17:47
 * Function:
 */

namespace Tanmo\Wechat\MiniProgram\Contracts;


interface ConfigContract
{
    public function getSessionUri();

    public function getAppId();

    public function getSecret();
}