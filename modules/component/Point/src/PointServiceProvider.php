<?php

/*
 * This file is part of ibrand/point.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Point;

use Event;
use iBrand\Component\Point\Repository\Eloquent\PointRepositoryEloquent;
use iBrand\Component\Point\Repository\PointRepository;
use Illuminate\Support\ServiceProvider;

class PointServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!class_exists('CreatePointTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_point_tables.php.sub' => database_path()."/migrations/{$timestamp}_create_point_tables.php",
            ], 'migrations');
        }

    }


    public function register()
    {
        $this->app->bind(PointRepository::class, PointRepositoryEloquent::class);
    }
}
