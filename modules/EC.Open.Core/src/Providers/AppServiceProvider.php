<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Core\Providers;

use iBrand\Component\Advert\AdvertServiceProvider;
use iBrand\Component\Discount\Contracts\AdjustmentContract;
use iBrand\Component\Discount\Providers\DiscountServiceProvider;
use iBrand\Component\Favorite\FavoriteServiceProvider;
use iBrand\Component\Order\Models\Adjustment;
use iBrand\Component\Order\Providers\OrderServiceProvider;
use iBrand\Component\Payment\Providers\PaymentServiceProvider;
use iBrand\Component\Point\PointServiceProvider;
use iBrand\Component\Product\Models\Goods;
use iBrand\Component\Product\Models\Product;
use iBrand\Component\Product\ProductServiceProvider;
use iBrand\Component\User\Models\User as BaseUser;
use iBrand\Component\User\UserServiceProvider;
use iBrand\EC\Open\Core\Auth\User;
use iBrand\EC\Open\Core\Console\BuildAddress;
use iBrand\EC\Open\Core\Console\BuildCoupon;
use iBrand\EC\Open\Core\Listeners\OrderEventListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (config('ibrand.app.secure')) {
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/app.php' => config_path('ibrand/app.php'),
            ]);
        }

        $this->commands([
            BuildAddress::class,
            BuildCoupon::class,
        ]);

        Event::subscribe(OrderEventListener::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/app.php', 'ibrand.app'
        );

        $this->registerComponent();

        $this->app->bind(BaseUser::class, User::class);
        $this->app->bind(AdjustmentContract::class, Adjustment::class);
        $this->app->bind(Goods::class,\iBrand\EC\Open\Core\Models\Goods::class);
        $this->app->bind(Product::class,\iBrand\EC\Open\Core\Models\Product::class);
    }

    protected function registerComponent()
    {
        $this->app->register(UserServiceProvider::class);
        $this->app->register(ProductServiceProvider::class);
        $this->app->register(DiscountServiceProvider::class);
        $this->app->register(\iBrand\Component\Category\ServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(\iBrand\Component\Address\ServiceProvider::class);
        $this->app->register(\iBrand\Component\Shipping\ShippingServiceProvider::class);
        $this->app->register(FavoriteServiceProvider::class);
        $this->app->register(AdvertServiceProvider::class);
        $this->app->register(PaymentServiceProvider::class);
        $this->app->register(PointServiceProvider::class);
    }
}
