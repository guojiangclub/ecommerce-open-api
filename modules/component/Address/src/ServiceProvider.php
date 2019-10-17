<?php

/*
 * This file is part of ibrand/address.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Address;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     *  Boot the service provider.
     */
    public function boot()
    {
        if (!class_exists('CreateAddressesTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/../migrations/create_addresses_tables.php.stub' =>
                    database_path() . "/migrations/{$timestamp}_create_addresses_tables.php",
            ], 'migrations');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(RepositoryContract::class, Repository::class);

        $this->app->alias(RepositoryContract::class, 'repository.address');
    }


}
