<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Advert;

use iBrand\Component\Advert\Repository\AdvertItemRepository;
use iBrand\Component\Advert\Repository\AdvertRepository;
use iBrand\Component\Advert\Repository\Eloquent\AdvertItemRepositoryEloquent;
use iBrand\Component\Advert\Repository\Eloquent\AdvertRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AdvertServiceProvider extends ServiceProvider
{
    /**
     *  Boot the service provider.
     */
    public function boot()
    {
        if (!class_exists('CreateAdvertTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_advert_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_advert_tables.php",
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->app->bind(AdvertRepository::class, AdvertRepositoryEloquent::class);
        $this->app->bind(AdvertItemRepository::class, AdvertItemRepositoryEloquent::class);
    }
}
