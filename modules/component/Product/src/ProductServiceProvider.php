<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Product;

use iBrand\Component\Product\Repositories\Eloquent\GoodsRepositoryEloquent;
use iBrand\Component\Product\Repositories\Eloquent\ProductRepositoryEloquent;
use iBrand\Component\Product\Repositories\GoodsRepository;
use iBrand\Component\Product\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (!class_exists('CreateProductTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_product_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_product_tables.php",
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->app->bind(GoodsRepository::class, GoodsRepositoryEloquent::class);
        $this->app->bind(ProductRepository::class, ProductRepositoryEloquent::class);
    }
}
