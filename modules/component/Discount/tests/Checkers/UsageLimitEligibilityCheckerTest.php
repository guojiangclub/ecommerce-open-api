<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 17:22
 */

namespace GuoJiangClub\Component\Discount\Test\Checkers;


use GuoJiangClub\Component\Discount\Checkers\DatesEligibilityChecker;
use GuoJiangClub\Component\Discount\Checkers\UsageLimitEligibilityChecker;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Models\Discount;
use Faker\Factory;

class UsageLimitEligibilityCheckerTest extends BaseTest
{
    public function testIsEligible()
    {
        $faker = Factory::create('zh_CN');

        $discountChecker = $this->app->make(UsageLimitEligibilityChecker::class);


        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertTrue($result);


        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 100,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertFalse($result);

        //test usage limit null
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(1),
            'ends_at' => Carbon::now()->addDay(2),
        ]);

        $result = $discountChecker->isEligible($discount);

        $this->assertTrue($result);

    }
}