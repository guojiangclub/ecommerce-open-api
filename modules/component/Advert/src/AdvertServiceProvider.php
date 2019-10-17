<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert;

use Illuminate\Database\Eloquent\Relations\Relation;
use GuoJiangClub\Component\Advert\Repositories\AdvertItemRepository;
use GuoJiangClub\Component\Advert\Repositories\AdvertRepository;
use GuoJiangClub\Component\Advert\Repositories\Eloquent\AdvertItemRepositoryEloquent;
use GuoJiangClub\Component\Advert\Repositories\Eloquent\AdvertRepositoryEloquent;
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

        $this->publishes([
            __DIR__.'/../config/advert.php' => config_path('ibrand/advert.php'),
        ]);

        Relation::morphMap(
            config('ibrand.advert.models')
        );
    }

    public function register()
    {
        $this->app->bind(AdvertRepository::class, AdvertRepositoryEloquent::class);
        $this->app->bind(AdvertItemRepository::class, AdvertItemRepositoryEloquent::class);
    }
}
