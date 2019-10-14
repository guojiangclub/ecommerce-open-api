<?php

namespace GuoJiangClub\Component\Discount\Test\Repositories;


use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Discount\Test\BaseTest;
use GuoJiangClub\Component\Discount\Test\Models\User;
use Illuminate\Support\Carbon;

class CouponRepositoryTest extends BaseTest
{

    public function testFindActiveByUser()
    {
        $repository = $this->app->make(CouponRepository::class);

        $coupons = $repository->findActiveByUser($this->user->id, 0);

        $this->assertEquals(20, count($coupons));


        $coupons = $repository->findActiveByUser($this->user->id);

        $this->assertEquals(15, count($coupons));
    }

    public function testFindInvalidByUser()
    {
        $repository = $this->app->make(CouponRepository::class);

        $coupons = $repository->findActiveByUser($this->user->id, 0);

        $coupon = $coupons->random();

        $coupon->expires_at = Carbon::now()->addDay(-1);
        $coupon->save();

        $invalidCoupons = $repository->findInvalidByUser($this->user->id);

        $this->assertEquals(1, count($invalidCoupons));

        $coupon = $coupons->whereNotIn('discount_id', [$coupon->discount_id])->random();
        $discount = $coupon->discount;
        $discount->useend_at = Carbon::now()->addDay(-1);
        $discount->save();

        $invalidCoupons = $repository->findInvalidByUser($this->user->id);

        $invalidDiscountCoupons = Coupon::where('discount_id', $discount->id)->get();

        $this->assertEquals(1 + count($invalidDiscountCoupons), count($invalidCoupons));
    }

    public function testFindUsedByUser()
    {
        $repository = $this->app->make(CouponRepository::class);

        $coupons = $repository->findActiveByUser($this->user->id, 0);

        $coupon = $coupons->random();
        $coupon->used_at = Carbon::now()->addDay(-1);
        $coupon->save();

        $usedCoupons = $repository->findUsedByUser($this->user->id);
        $this->assertEquals(1, count($usedCoupons));
    }
}