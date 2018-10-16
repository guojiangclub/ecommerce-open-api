<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 15:20
 */

namespace iBrand\Component\Discount\Test\Checkers;

use iBrand\Component\Discount\Checkers\DiscountEligibilityChecker;
use iBrand\Component\Discount\Repositories\DiscountRepository;
use iBrand\Component\Discount\Test\BaseTest;
use iBrand\Component\Discount\Test\Models\Order;

class DiscountEligibilityCheckerTest extends BaseTest
{

    public function testIsEligible()
    {
        $repository =$this->app->make(DiscountRepository::class);
        $discountChecker = $this->app->make(DiscountEligibilityChecker::class);

        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 1, 'items_total' => 50, 'total' => 50,]);

        $discounts = $repository->findActive();

        $filtered = $discounts->filter(function ($item) use ($order,$discountChecker) {
            return $discountChecker->isEligible($order, $item);
        });

        $this->assertEquals(0,$filtered->count());
    }
}