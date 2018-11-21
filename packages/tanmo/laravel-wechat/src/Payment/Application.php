<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 11:25
 * Function:
 */

namespace Tanmo\Wechat\Payment;


use Tanmo\Wechat\Payment\Notify\Paid;
use Tanmo\Wechat\Payment\Notify\Refund as NotifyRefund;

class Application
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Application constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        if (is_array($config) && !empty($config)) {
            $this->config = new Config($config);
        }
    }

    /**
     * @return Order
     */
    public function order()
    {
        return app()->makeWith(Order::class, ['config' => $this->config]);
    }

    /**
     * @return \Tanmo\Wechat\Payment\Refund
     */
    public function refund()
    {
        return app()->makeWith(Refund::class, ['config' => $this->config]);
    }

    /**
     * @param \Closure $closure
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function paidNotify(\Closure $closure)
    {
        return (new Paid($this->config))->handle($closure);
    }

    /**
     * @param \Closure $closure
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refundNotify(\Closure $closure)
    {
        return (new NotifyRefund($this->config))->handle($closure);
    }

    /**
     * @return Config
     */
    public function config()
    {
        return $this->config;
    }
}