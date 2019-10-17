<?php

/*
 * This file is part of ibrand/shipping.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Shipping;

use GuoJiangClub\Component\Shipping\Console\ShippingCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/29
 * Time: 14:36.
 */
class ShippingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!class_exists('CreateShippingTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_shipping_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_shipping_tables.php",
            ], 'migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                ShippingCommand::class,
            ]);
        }
    }
}
