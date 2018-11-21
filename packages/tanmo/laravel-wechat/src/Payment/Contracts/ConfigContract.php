<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 11:34
 * Function:
 */

namespace Tanmo\Wechat\Payment\Contracts;


interface ConfigContract
{
    public function getAppId();

    public function getAdbAppId();

    public function getAppSecret();

    public function getMchId();

    public function getKey();

    public function getCertPath();

    public function getKeyPath();

    public function getNotifyUrl();
}