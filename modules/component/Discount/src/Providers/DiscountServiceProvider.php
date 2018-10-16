<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Providers;

use iBrand\Component\Discount\Actions\OrderFixedDiscountAction;
use iBrand\Component\Discount\Actions\OrderPercentageDiscountAction;
use iBrand\Component\Discount\Checkers\CartQuantityRuleChecker;
use iBrand\Component\Discount\Checkers\ItemTotalRuleChecker;
use iBrand\Component\Discount\Models\Coupon;
use iBrand\Component\Discount\Policies\CouponPolicy;
use iBrand\Component\Discount\Repositories\CouponRepository;
use iBrand\Component\Discount\Repositories\DiscountRepository;
use iBrand\Component\Discount\Repositories\Eloquent\CouponRepositoryEloquent;
use iBrand\Component\Discount\Repositories\Eloquent\DiscountRepositoryEloquent;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;

class DiscountServiceProvider extends ServiceProvider
{
    protected $policies = [
        Coupon::class => CouponPolicy::class,
    ];

    /**
     * bootstrap, add routes.
     *
     * @param GateContract $gate
     */
    public function boot(GateContract $gate)
    {
        if (!class_exists('CreateDiscountTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../../migrations/create_discount_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_discount_tables.php",
            ], 'migrations');
        }

        $this->registerPolicies($gate);
    }

    private function registerPolicies(GateContract $gate)
    {
        foreach ($this->policies as $key => $value) {
            $gate->policy($key, $value);
        }
    }

    public function register()
    {
        $this->app->bind(
            ItemTotalRuleChecker::class,
            ItemTotalRuleChecker::class
        );
        $this->app->alias(ItemTotalRuleChecker::class, ItemTotalRuleChecker::TYPE);

        $this->app->bind(
            CartQuantityRuleChecker::class,
            CartQuantityRuleChecker::class
        );
        $this->app->alias(CartQuantityRuleChecker::class, CartQuantityRuleChecker::TYPE);

        $this->app->bind(
            OrderFixedDiscountAction::class,
            OrderFixedDiscountAction::class
        );
        $this->app->alias(OrderFixedDiscountAction::class, OrderFixedDiscountAction::TYPE);

        $this->app->bind(
            OrderPercentageDiscountAction::class,
            OrderPercentageDiscountAction::class
        );
        $this->app->alias(OrderPercentageDiscountAction::class, OrderPercentageDiscountAction::TYPE);
        $this->app->bind(DiscountRepository::class, DiscountRepositoryEloquent::class);
        $this->app->bind(CouponRepository::class, CouponRepositoryEloquent::class);
    }
}
