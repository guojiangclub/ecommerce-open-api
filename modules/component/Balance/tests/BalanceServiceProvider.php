<?php

/*
 * This file is part of ibrand/balace.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Balance\Test;

use Illuminate\Support\ServiceProvider;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
        }
    }

    /**
     * Register Passport's migration files.
     */
    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__.'/database');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
