<?php

namespace App\Providers;

use App\Contracts\JwtAuthContract;
use App\Services\Auth\WeChat;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LoginServiceProvider extends ServiceProvider
{
    /**
     * 登录源
     *
     * @var array
     */
    private $via = [
        'wechat' => WeChat::class,
    ];

    /**
     * 默认登录源
     *
     * @var string
     */
    private $defaultVia = 'wechat';

    /**
     * Header Key
     *
     * @var string
     */
    private $header = 'x-login-via';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoginHandler();

        $this->app->bind(JwtAuthContract::class, function (Application $app) {
            $via = request()->header($this->header) ?? $this->defaultVia;

            if (!array_key_exists($via, $this->via)) {
                api()->errorMethodNotAllowed("Login via [$via] error");
            }

            return $app['auth.' . $via];
        });
    }

    /**
     * Register all login handler
     */
    public function registerLoginHandler()
    {
        foreach ($this->via as $key => $handler) {
            $this->app->singleton('auth.' . $key, $handler);
        }
    }
}
