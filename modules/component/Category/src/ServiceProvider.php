<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Category;

use Illuminate\Support\ServiceProvider as LaravelServicePorvider;

class ServiceProvider extends LaravelServicePorvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (!class_exists('CreateCategoryTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/../migrations/create_category_tables.php.stub' =>
                    database_path() . "/migrations/{$timestamp}_create_category_tables.php",
            ], 'migrations');
        }
    }


    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(RepositoryContract::class, Repository::class);
    }
}
