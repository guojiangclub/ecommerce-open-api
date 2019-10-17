<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Product;

use GuoJiangClub\Component\Product\Repositories\Eloquent\GoodsRepositoryEloquent;
use GuoJiangClub\Component\Product\Repositories\Eloquent\ProductRepositoryEloquent;
use GuoJiangClub\Component\Product\Repositories\GoodsRepository;
use GuoJiangClub\Component\Product\Repositories\ProductRepository;
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
