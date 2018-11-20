<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay;

use iBrand\Component\Pay\Charges\DefaultCharge;
use iBrand\Component\Pay\Contracts\PayChargeContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-19
 * Time: 12:21.
 */
class PayServiceProvider extends ServiceProvider
{
    protected $namespace = 'iBrand\Component\Pay\Http\Controllers';

    /**
     *  Boot the service provider.
     */
    public function boot()
    {
        if (!class_exists('CreatePayTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_pay_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_pay_tables.php",
            ], 'migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config.php' => config_path('ibrand/pay.php'),
            ]);
        }

        $this->loadRoutes();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'ibrand.pay'
        );

        $config = config('ibrand.pay');

        $this->app->singleton(PayChargeContract::class, function () use ($config) {
            switch ($config['driver']) {
                case 'default':
                    return new DefaultCharge();
            }

            throw new InvalidArgumentException("Unsupported driver [{$config['driver']}]");
        });

        $this->app->alias(PayChargeContract::class, 'ibrand.pay.charge');
    }

    public function loadRoutes()
    {
        $routeAttr = config('ibrand.pay.route', []);

        Route::group(array_merge(['namespace' => $this->namespace], $routeAttr), function ($router) {
            $router->post('wechat/{app}', 'WechatPayNotifyController@notify')->name('pay.wechat.notify');
            $router->post('alipay/{app}', 'AliPayNotifyController@notify')->name('pay.alipay.notify');
        });
    }
}
