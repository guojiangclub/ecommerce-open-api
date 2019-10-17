<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Providers;

use GuoJiangClub\EC\Open\Server\Schedule\OrderRemindSchedule;
use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'server');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config.php' => config_path('ibrand/ec-open-api.php'),
            ]);

            $this->publishes([
                __DIR__.'/../../resources/assets' => public_path(),
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'ibrand.ec-open-api'
        );

        $this->app->register(RouteServiceProvider::class);

        //$this->app->make('iBrand\Scheduling\ScheduleList')->add(OrderRemindSchedule::class);
    }
}
