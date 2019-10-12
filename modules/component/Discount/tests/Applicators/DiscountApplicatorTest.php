<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 20:05
 */

namespace GuoJiangClub\Component\Discount\Test\Applicators;

use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Actions\OrderFixedDiscountAction;
use GuoJiangClub\Component\Discount\Actions\OrderPercentageDiscountAction;
use GuoJiangClub\Component\Discount\Applicators\DiscountApplicator;
use GuoJiangClub\Component\Discount\Checkers\DiscountEligibilityChecker;
use GuoJiangClub\Component\Discount\Checkers\ItemTotalRuleChecker;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use GuoJiangClub\Component\Discount\Test\Models\Order;
use Faker\Factory;
use GuoJiangClub\Component\Discount\Models\Action;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Models\Rule;
use GuoJiangClub\Component\Discount\Test\Models\OrderItem;

class DiscountApplicatorTest extends BaseTest
{
    public function testCalculate()
    {
        $repository =$this->app->make(DiscountRepository::class);
        $discountChecker = $this->app->make(DiscountEligibilityChecker::class);

        //test order fixed discount action calculate
        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 2, 'items_total' => 80, 'total' => 80,]);

        $discounts = $repository->findActive();

        $filtered = $discounts->filter(function ($item) use ($order,$discountChecker) {
            return $discountChecker->isEligible($order, $item);
        });

        $this->assertEquals(1,$filtered->count());

        $applicator = $this->app->make(DiscountApplicator::class);

        $discount = $filtered->first();
        $applicator->calculate($order,$discount);


        $this->assertEquals(-10,$discount->adjustmentTotal);

        //test order percentage discount action calculate

        $faker = Factory::create('zh_CN');
        //创建一个有效的优惠活动,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        //订单满120打8折
        Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 120])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderPercentageDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 80])]);


        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 1, 'items_total' => 120, 'total' => 120,]);

        $applicator->calculate($order,$discount);

        $this->assertEquals(-24,$discount->adjustmentTotal);

    }


    public function testApply()
    {
        $repository =$this->app->make(DiscountRepository::class);
        $discountChecker = $this->app->make(DiscountEligibilityChecker::class);

        //test order fixed discount action calculate
        $order = new Order(['user_id' => $this->user->id]);

        $orderItem1 = new OrderItem(['order_id'=>$order->id,'item_id'=>1,'item_name'=>'商品1','type'=>'goods','quantity'=>1,
            'unit_price'=>30,'units_total'=>30,'total'=>30]);
        $orderItem2 = new OrderItem(['order_id'=>$order->id,'item_id'=>2,'item_name'=>'商品2','type'=>'goods','quantity'=>1,
            'unit_price'=>50,'units_total'=>50,'total'=>50]);

        $order->addItem($orderItem1);
        $order->addItem($orderItem2);

        $discounts = $repository->findActive();

        $filtered = $discounts->filter(function ($item) use ($order,$discountChecker) {
            return $discountChecker->isEligible($order, $item);
        });

        $this->assertEquals(1,$filtered->count());

        $applicator = $this->app->make(DiscountApplicator::class);
        $discount = $filtered->first();
        $applicator->apply($order,$discount);
        $this->assertEquals(-10,$order->adjustments_total);
        $this->assertEquals(70,$order->total);



        //test order percentage discount action calculate
        $faker = Factory::create('zh_CN');
        //创建一个有效的优惠活动,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);
        //订单满120打8折
        Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 120])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderPercentageDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 80])]);


        $order = new Order(['user_id' => $this->user->id]);

        $orderItem1 = new OrderItem(['order_id'=>$order->id,'item_id'=>1,'item_name'=>'商品1','type'=>'goods','quantity'=>1,
            'unit_price'=>120,'units_total'=>120,'total'=>120]);
        $order->addItem($orderItem1);
        $discounts = collect([$discount]);
        $filtered = $discounts->filter(function ($item) use ($order,$discountChecker) {
            return $discountChecker->isEligible($order, $item);
        });

        $this->assertEquals(1,$filtered->count());

        $applicator = $this->app->make(DiscountApplicator::class);
        $discount = $filtered->first();
        $applicator->apply($order,$discount);
        $this->assertEquals(-24,$order->adjustments_total);
        $this->assertEquals(96,$order->total);


        //test coupon apply
    }
    public function testCouponApply()
    {
        $faker = Factory::create('zh_CN');
        //创建一个有效的优惠活动,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);
        //订单满120打8折
        Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 120])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderPercentageDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 80])]);

        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id]);

        $order = new Order(['user_id' => $this->user->id]);

        $orderItem1 = new OrderItem(['order_id'=>$order->id,'item_id'=>1,'item_name'=>'商品1','type'=>'goods','quantity'=>1,
            'unit_price'=>120,'units_total'=>120,'total'=>120]);
        $order->addItem($orderItem1);


        $applicator = $this->app->make(DiscountApplicator::class);

        $applicator->apply($order,$coupon);
        $this->assertEquals(-24,$order->adjustments_total);
        $this->assertEquals(96,$order->total);
    }


}