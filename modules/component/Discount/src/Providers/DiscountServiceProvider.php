<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Providers;

use GuoJiangClub\Component\Discount\Actions\OrderFixedDiscountAction;
use GuoJiangClub\Component\Discount\Actions\OrderPercentageDiscountAction;
use GuoJiangClub\Component\Discount\Checkers\CartQuantityRuleChecker;
use GuoJiangClub\Component\Discount\Checkers\ItemTotalRuleChecker;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Policies\CouponPolicy;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use GuoJiangClub\Component\Discount\Repositories\Eloquent\CouponRepositoryEloquent;
use GuoJiangClub\Component\Discount\Repositories\Eloquent\DiscountRepositoryEloquent;
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
