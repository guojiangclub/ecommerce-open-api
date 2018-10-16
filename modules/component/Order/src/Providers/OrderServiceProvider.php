<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Order\Providers;

use iBrand\Component\Order\Models\Order;
use iBrand\Component\Order\Policies\OrderPolicy;
use iBrand\Component\Order\Repositories\CommentRepository;
use iBrand\Component\Order\Repositories\Eloquent\CommentRepositoryEloquent;
use iBrand\Component\Order\Repositories\Eloquent\OrderRepositoryEloquent;
use iBrand\Component\Order\Repositories\OrderRepository;
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
