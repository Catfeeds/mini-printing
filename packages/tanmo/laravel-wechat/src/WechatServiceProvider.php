<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 14:34
 * Function:
 */

namespace Tanmo\Wechat;


use Illuminate\Support\ServiceProvider;
use Tanmo\Wechat\Providers\MiniProgram;

class WechatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
    }

    /**
     * 注册所有的服务
     */
    public function registerProviders()
    {
        $this->app->register(MiniProgram::class);
    }
}