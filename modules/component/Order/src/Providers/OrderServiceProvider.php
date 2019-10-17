<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Providers;

use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Policies\OrderPolicy;
use GuoJiangClub\Component\Order\Repositories\CommentRepository;
use GuoJiangClub\Component\Order\Repositories\Eloquent\CommentRepositoryEloquent;
use GuoJiangClub\Component\Order\Repositories\Eloquent\OrderRepositoryEloquent;
use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class,
    ];

    /**
     * bootstrap.
     */
    public function boot(GateContract $gate)
    {
        if (!class_exists('CreateOrderTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../../migrations/create_order_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_order_tables.php",
            ], 'migrations');
        }

        $this->registerPolicies($gate);
    }

    public function register()
    {
        $this->app->bind(CommentRepository::class, CommentRepositoryEloquent::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryEloquent::class);
    }

    private function registerPolicies(GateContract $gate)
    {
        foreach ($this->policies as $key => $value) {
            $gate->policy($key, $value);
        }
    }
}
