<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Console;

use Carbon\Carbon;
use Faker\Factory;
use GuoJiangClub\Component\Discount\Actions\OrderFixedDiscountAction;
use GuoJiangClub\Component\Discount\Checkers\CartQuantityRuleChecker;
use GuoJiangClub\Component\Discount\Checkers\ItemTotalRuleChecker;
use GuoJiangClub\Component\Discount\Models\Action;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Models\Rule;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use Illuminate\Console\Command;

class BuildCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibrand:build-coupon {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'build some coupons to user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');

        $faker = Factory::create('zh_CN');

        $this->seedCoupon($faker);

        $this->seedUserCoupon($userId);

        $this->info('User\'s coupons created successfully.');
    }

    /**
     * @param $faker
     */
    protected function seedCoupon($faker)
    {
        //创建一个未开始的优惠券
        Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(3, 100),
            'used' => $faker->randomDigitNotNull,
            'starts_at' => Carbon::now()->addDay(1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);

        //创建一个状态禁用的优惠券
        Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(3, 100),
            'used' => $faker->randomDigitNotNull,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'status' => 0,
            'coupon_based' => 1,
        ]);

        //创建一个已经全部用完优惠券
        Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => 100,
            'used' => 100,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'status' => 0,
            'coupon_based' => 1,
        ]);

        //创建一个有效的优惠优惠券,订单满数量减
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);
        //购物车数量满2,则减去10元
        Rule::create(['discount_id' => $discount->id, 'type' => CartQuantityRuleChecker::TYPE, 'configuration' => json_encode(['count' => 2])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['amount' => 10])]);

        //创建一个有效的优惠优惠券,订单满金额减
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);
        //订单满100-10
        Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 100])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['amount' => 10])]);

        //创建一个有效的优惠优惠券,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);
        //订单满100打8折
        Rule::create(['discount_id' => $discount->id, 'type' => ItemTotalRuleChecker::TYPE, 'configuration' => json_encode(['amount' => 120])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 80])]);

        //创建一个有效的优惠券,订单满金额打折
        $discount = Discount::create([
            'title' => $faker->word,
            'label' => $faker->word,
            'usage_limit' => $faker->numberBetween(80, 100),
            'used' => 20,
            'starts_at' => Carbon::now()->addDay(-1),
            'ends_at' => Carbon::now()->addDay(2),
            'coupon_based' => 1,
        ]);
        //订单数量满3件打9折
        Rule::create(['discount_id' => $discount->id, 'type' => CartQuantityRuleChecker::TYPE, 'configuration' => json_encode(['count' => 3])]);
        Action::create(['discount_id' => $discount->id, 'type' => OrderFixedDiscountAction::TYPE, 'configuration' => json_encode(['percentage' => 90])]);
    }

    protected function seedUserCoupon($userId)
    {
        $repository = app(DiscountRepository::class);

        //get active discount coupons
        $discounts = $repository->findActive(1);

        //生成20张有效券
        for ($i = 0; $i < 20; ++$i) {
            Coupon::create(['discount_id' => $discounts->random()->id, 'user_id' => $userId]);
        }
    }
}
