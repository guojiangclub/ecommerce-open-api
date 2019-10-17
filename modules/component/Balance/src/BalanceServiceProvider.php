<?php

/*
 * This file is part of ibrand/balance.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Balance;

use Event;
use GuoJiangClub\Component\Balance\Listeners\BalanceOrderPaidSuccess;
use Illuminate\Support\ServiceProvider;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (!class_exists('CreateBalanceTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_balance_table.php.stub' => database_path()."/migrations/{$timestamp}_create_balance_table.php",
            ], 'migrations');
        }

        Event::subscribe(BalanceOrderPaidSuccess::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
