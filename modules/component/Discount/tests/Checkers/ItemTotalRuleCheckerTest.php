<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 16:54
 */

namespace GuoJiangClub\Component\Discount\Test\Checkers;


use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Actions\OrderFixedDiscountAction;
use GuoJiangClub\Component\Discount\Checkers\ItemTotalRuleChecker;
use GuoJiangClub\Component\Discount\Models\Action;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Models\Rule;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use Faker\Factory;
use GuoJiangClub\Component\Discount\Test\Models\Order;

class ItemTotalRuleCheckerTest extends BaseTest
{
    public function testIsEligible()
    {
        $faker = Factory::create('zh_CN');

        $discountChecker = $this->app->make(ItemTotalRuleChecker::class);

        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 1, 'items_total' => 50, 'total' => 50,]);

        //创建一个有效的优惠活动,订单满数量减
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        //购物车数量满100,则减去10元
        $rule = Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 100])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['amount' => 10])]);


        $result = $discountChecker->isEligible($order, json_decode($rule->configuration, true),$discount);

        $this->assertFalse($result);

        $order = Order::create(['user_id' => $this->user->id
            , 'count' => 2, 'items_total' => 50, 'total' => 100,]);

        $result = $discountChecker->isEligible($order, json_decode($rule->configuration, true),$discount);

        $this->assertTrue($result);
    }
}