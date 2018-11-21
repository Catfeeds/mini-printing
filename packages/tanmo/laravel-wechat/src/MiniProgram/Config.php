<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 15:31
 * Function:
 */

namespace Tanmo\Wechat\MiniProgram;


use Tanmo\Wechat\MiniProgram\Contracts\ConfigContract;

class Config implements ConfigContract
{
    /**
     * 小程序APP_ID
     *
     * @var string
     */
    protected $appId;

    /**
     * 小程序秘钥
     *
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $sessionUri;

    /**
     * Config constructor.
     * @param $appId
     * @param $secret
     */
    public function __construct($appId, $secret)
    {
        $this->sessionUri = config('wechat.jscode2session');
        $this->appId = $appId;
        $this->secret = $secret;
    }

    public function getSessionUri()
    {
        return $this->sessionUri;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}