<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/22
 * Time: 11:22
 *
 *                                _oo8oo_
 *                               o8888888o
 *                               88" . "88
 *                               (| -_- |)
 *                               0\  =  /0
 *                             ___/'==='\___
 *                           .' \\|     |// '.
 *                          / \\|||  :  |||// \
 *                         / _||||| -:- |||||_ \
 *                        |   | \\\  -  /// |   |
 *                        | \_|  ''\---/''  |_/ |
 *                        \  .-\__  '-'  __/-.  /
 *                      ___'. .'  /--.--\  '. .'___
 *                   ."" '<  '.___\_<|>_/___.'  >' "".
 *                  | | :  `- \`.:`\ _ /`:.`/ -`  : | |
 *                  \  \ `-.   \_ __\ /__ _/   .-` /  /
 *              =====`-.____`.___ \_____/ ___.`____.-`=====
 *                                `=---=`
 *
 *
 *             ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 *                         佛祖保佑    永无BUG
 *
 */

namespace Tanmo\Wechat\Support;


use Ixudra\Curl\Facades\Curl;
use Tanmo\Wechat\Payment\Contracts\ConfigContract;

class BaseClient
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
     * @param string $apiUrl
     * @param array $params
     * @param string $method
     * @param array $options
     * @return array
     */
    protected function request(string $apiUrl, array $params = [], $method = 'post', array $options = [])
    {
        $curl = Curl::to($apiUrl);

        ///
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                $curl->withOption($key, $option);
            }
        }

        ///
        $base = [
            'appid' => $this->config->getAppId(),
            'mch_id' => $this->config->getMchId(),
            'nonce_str' => uniqid(),
        ];

        ///
        $params = array_filter(array_merge($params, $base));
        $params['sign'] = gen_sign($params, $this->config->getKey());
        $response = $curl->withData(XML::build($params))->$method();

        ///
        return XML::parse($response);
    }

    /**
     * @param string $apiUrl
     * @param array $params
     * @param string $method
     * @param array $options
     * @return array
     */
    protected function safeRequest(string $apiUrl, array $params = [], $method = 'post', array $options = [])
    {
        $options = array_merge([
            'SSLCERTTYPE' => 'PEM',
            'SSLCERT' => $this->config->getCertPath(),
            'SSLKEYTYPE' => 'PEM',
            'SSLKEY' => $this->config->getKeyPath()
        ], $options);

        return $this->request($apiUrl, $params, $method, $options);
    }
}