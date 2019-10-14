<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 17:38
 */

namespace GuoJiangClub\Component\Discount\Test\Checkers;


use GuoJiangClub\Component\Discount\Actions\OrderFixedDiscountAction;
use GuoJiangClub\Component\Discount\Checkers\CartQuantityRuleChecker;
use GuoJiangClub\Component\Discount\Checkers\CouponEligibilityChecker;
use GuoJiangClub\Component\Discount\Models\Action;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Models\Rule;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use GuoJiangClub\Component\Discount\Test\Models\Order;
use Faker\Factory;
use Illuminate\Support\Carbon;

class CouponEligibilityCheckerTest extends BaseTest
{
    public function testIsEligible()
    {
        $faker = Factory::create('zh_CN');
        $repository =$this->app->make(CouponRepository::class);
        $couponChecker = $this->app->make(CouponEligibilityChecker::class);

        //test invalid order.
        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 1, 'items_total' => 50, 'total' => 50,]);

        $coupons = $repository->findActiveByUser($this->user->id);

        $filtered = $coupons->filter(function ($item) use ($order,$couponChecker) {
            return $couponChecker->isEligible($order, $item);
        });

        $this->assertEquals(0,$filtered->count());


        //test valid order.
        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 2, 'items_total' => 50, 'total' => 50,]);

        $coupons = $repository->findActiveByUser($this->user->id);

        $filtered = $coupons->filter(function ($item) use ($order,$couponChecker) {
            return $couponChecker->isEligible($order, $item);
        });

        $this->assertGreaterThan(0,$filtered->count());

        //test invalid order for coupon error time.
        //创建一个有效的优惠券,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-2),
            'ends_at' => Carbon::now()->addDay(-1),
            'coupon_based' => 1,
        ]);
        //订单数量满3件打9折
        Rule::create(['discount_id' => $discount->id, 'type' => CartQuantityRuleChecker::TYPE, 'configuration' => json_encode(['count' => 3])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 90])]);

        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id]);

        $result = $couponChecker->isEligible($order, $coupon);

        $this->assertFalse($result);

    }
}