<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 16:02
 * Function:
 */

namespace Tanmo\Wechat\MiniProgram;


use Tanmo\Wechat\MiniProgram\Auth\Auth;

class Application
{
    /**
     * @var mixed
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
            $this->config = new Config($config['app_id'], $config['secret']);
        }
    }

    /**
     * @return Auth
     */
    public function auth()
    {
        return app()->makeWith(Auth::class, ['config' => $this->config]);
    }
}