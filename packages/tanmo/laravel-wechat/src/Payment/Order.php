<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 11:27
 * Function:
 */

namespace Tanmo\Wechat\Payment;


use Ixudra\Curl\Facades\Curl;
use Tanmo\Wechat\Payment\Contracts\ConfigContract;
use Tanmo\Wechat\Support\XML;

class Order
{
    /**
     * @var ConfigContract
     */
    protected $config;

    /**
     * Order constructor.
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $params
     * @return array
     */
    public function unify(array $params)
    {
        if (empty($params['spbill_create_ip'])) {
            $params['spbill_create_ip'] = ('NATIVE' === $params['trade_type']) ? get_server_ip() : get_client_ip();
        }

        $params['appid'] = $this->config->getAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = uniqid();
        $params['notify_url'] = $params['notify_url'] ?? $this->config->getNotifyUrl();
        $params['sign'] = gen_sign($params, $this->config->getKey());

        $xml = XML::build($params);
        $response = Curl::to('https://api.mch.weixin.qq.com/pay/unifiedorder')->withData($xml)->post();

        return XML::parse($response);
    }

    public function adbunify(array $params)
    {
        if (empty($params['spbill_create_ip'])) {
            $params['spbill_create_ip'] = ('NATIVE' === $params['trade_type']) ? get_server_ip() : get_client_ip();
        }

        $params['appid'] = $this->config->getAdbAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = uniqid();
        $params['notify_url'] = $params['notify_url'] ?? $this->config->getNotifyUrl();
        $params['sign'] = gen_sign($params, $this->config->getKey());

        $xml = XML::build($params);
        $response = Curl::to('https://api.mch.weixin.qq.com/pay/unifiedorder')->withData($xml)->post();

        return XML::parse($response);
    }
}