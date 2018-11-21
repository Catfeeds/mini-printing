<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 15:29
 * Function:
 */

namespace Tanmo\Wechat\MiniProgram\Auth;


use Ixudra\Curl\Facades\Curl;
use Tanmo\Wechat\Exceptions\WechatException;
use Tanmo\Wechat\MiniProgram\Contracts\ConfigContract;

class Auth
{
    /**
     * @var ConfigContract
     */
    protected $config;

    /**
     * @var array
     */
    protected $session;

    /**
     * @var
     */
    protected $encryptedData;

    /**
     * @var
     */
    protected $iv;

    /**
     * @var DataCrypt
     */
    protected $crypt;

    /**
     * Auth constructor.
     * @param ConfigContract $config
     * @param DataCrypt $crypt
     */
    public function __construct(ConfigContract $config, DataCrypt $crypt)
    {
        $this->config = $config;
        $this->crypt = $crypt;
    }

    /**
     * @param $code
     * @return $this
     * @throws WechatException
     */
    public function code($code)
    {
        $response = Curl::to($this->config->getSessionUri())->withData([
            'appid' => $this->config->getAppId(),
            'secret' => $this->config->getSecret(),
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ])->get();

        ///
        $session = json_decode($response, true);
        if (isset($session['errcode'])) {
            throw new WechatException($session['errmsg'], $session['errcode']);
        }

        ///
        $this->session = $session;

        return $this;
    }

    /**
     * @param $encryptedData
     * @return $this
     */
    public function encryptedData($encryptedData)
    {
        $this->encryptedData = $encryptedData;
        return $this;
    }

    /**
     * @param $iv
     * @return $this
     */
    public function iv($iv)
    {
        $this->iv = $iv;
        return $this;
    }

    /**
     * @return array
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * @return array
     */
    public function user()
    {
        $data = $this->crypt->decrypt(
            $this->config->getAppId(),
            $this->session['session_key'],
            $this->encryptedData,
            $this->iv
        );

        return json_decode($data, true);
    }
}