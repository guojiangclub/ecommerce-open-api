<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Server\Providers;

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
    }
}
