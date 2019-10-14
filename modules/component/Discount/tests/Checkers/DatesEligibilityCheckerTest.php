<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 16:31
 */

namespace GuoJiangClub\Component\Discount\Test\Checkers;


use GuoJiangClub\Component\Discount\Checkers\DatesEligibilityChecker;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Models\Discount;
use Faker\Factory;

class DatesEligibilityCheckerTest extends BaseTest
{
    public function testIsEligible()
    {
        $faker = Factory::create('zh_CN');

        $discountChecker = $this->app->make(DatesEligibilityChecker::class);


        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertFalse($result);


        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertTrue($result);

        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-2),
            'ends_at' => Carbon::now()->addDay(-1),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertFalse($result);
    }

    public function testCouponIsEligible(){
        $faker = Factory::create('zh_CN');

        $checker = $this->app->make(DatesEligibilityChecker::class);

        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        //test coupon expired_at
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id,'expires_at'=>Carbon::now()->addDay(1)]);
        $result = $checker->isEligible($coupon);
        $this->assertTrue($result);

        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id,'expires_at'=>Carbon::now()->addDay(-1)]);
        $result = $checker->isEligible($coupon);
        $this->assertFalse($result);

        //test coupon usestart_at and endstart_at

        //test active time range.
        $discount->usestart_at=Carbon::now()->addDay(-1);
        $discount->useend_at=Carbon::now()->addDay(1);
        $discount->save();
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id,'expires_at'=>Carbon::now()->addDay(1)]);
        $result = $checker->isEligible($coupon);
        $this->assertTrue($result);

        //test error usestart_at
        $discount->usestart_at=Carbon::now()->addDay(1);
        $discount->save();
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id,'expires_at'=>Carbon::now()->addDay(1)]);
        $result = $checker->isEligible($coupon);
        $this->assertFalse($result);

        //test right usestart_at
        $discount->usestart_at=Carbon::now()->addDay(-1);
        $discount->save();
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id,'expires_at'=>Carbon::now()->addDay(1)]);
        $result = $checker->isEligible($coupon);
        $this->assertTrue($result);

        //test error useend_at
        $discount->useend_at=Carbon::now()->addDay(-1);
        $discount->save();
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id]);
        $result = $checker->isEligible($coupon);
        $this->assertFalse($result);

        //test right useend_at
        $discount->useend_at=Carbon::now()->addDay(1);
        $discount->save();
        $coupon =  Coupon::create(['discount_id'=>$discount->id,'user_id'=>$this->user->id]);
        $result = $checker->isEligible($coupon);
        $this->assertTrue($result);


    }
}