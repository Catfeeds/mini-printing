<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 11:37
 * Function:
 */

namespace Tanmo\Wechat\Payment;


use Tanmo\Wechat\Payment\Contracts\ConfigContract;

class Config implements ConfigContract
{
    protected $appId;

    protected $appSecret;

    protected $mchId;

    protected $key;

    protected $certPath;

    protected $keyPath;

    protected $notifyUrl;

    protected $adbAppId;

    /**
     * Config constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->appId = $config['app_id'];
        $this->adbAppId = $config['adb_app_id'];
        $this->mchId = $config['mch_id'];
        $this->key = $config['key'];
        $this->notifyUrl = $config['notify_url'];
        $this->appSecret = isset($config['secret']) ? $config['secret'] : '';
        $this->certPath = isset($config['cert_path']) ? $config['cert_path'] : '';
        $this->keyPath = isset($config['key_path']) ? $config['key_path'] : '';

    }
    public function getAdbAppId(){
        return $this->adbAppId;
    }
    public function getAppId()
    {
        // TODO: Implement getAppId() method.
        return $this->appId;
    }

    public function getAppSecret()
    {
        // TODO: Implement getAppSecret() method.
        return $this->appSecret;
    }

    public function getMchId()
    {
        // TODO: Implement getMchId() method.
        return $this->mchId;
    }

    public function getKey()
    {
        // TODO: Implement getKey() method.
        return $this->key;
    }

    public function getCertPath()
    {
        // TODO: Implement getCertPath() method.
        return $this->certPath;
    }

    public function getKeyPath()
    {
        // TODO: Implement getKeyPath() method.
        return $this->keyPath;
    }

    public function getNotifyUrl()
    {
        // TODO: Implement getNotifyUrl() method.
        return $this->notifyUrl;
    }
}