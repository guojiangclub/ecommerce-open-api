<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Services;

use Exception;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Discount\Applicators\DiscountApplicator;
use GuoJiangClub\Component\Discount\Checkers\CouponEligibilityChecker;
use GuoJiangClub\Component\Discount\Checkers\DatesEligibilityChecker;
use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;
use GuoJiangClub\Component\Discount\Models\Coupon;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use GuoJiangClub\EC\Open\Core\Discount\Checkers\DiscountEligibilityChecker;
use GuoJiangClub\EC\Open\Core\Discount\Contracts\DiscountItemContract;
use Illuminate\Support\Collection;


class DiscountService
{
    private $discountRepository;
    private $discountChecker;
    private $couponChecker;
    private $couponRepository;
    protected $applicator;
    protected $datesEligibilityChecker;

    public function __construct(DiscountRepository $discountRepository
        , DiscountEligibilityChecker $discountEligibilityChecker
        , CouponRepository $couponRepository
        , CouponEligibilityChecker $couponEligibilityChecker
        , DiscountApplicator $discountApplicator
        , DatesEligibilityChecker $datesEligibilityChecker)
    {
        $this->discountRepository = $discountRepository;
        $this->discountChecker = $discountEligibilityChecker;
        $this->couponRepository = $couponRepository;
        $this->couponChecker = $couponEligibilityChecker;
        $this->applicator = $discountApplicator;
        $this->datesEligibilityChecker = $datesEligibilityChecker;
    }

    public function getEligibilityDiscounts(DiscountSubjectContract $subject)
    {
        try {
            $discounts = $this->discountRepository->findActive(0);
            if (0 == count($discounts)) {
                return false;
            }

            $filtered = $discounts->filter(function ($item) use ($subject) {
                return $this->discountChecker->isEligible($subject, $item);
            });

            if (0 == count($filtered)) {
                return false;
            }

            foreach ($filtered as $item) {
                $this->applicator->calculate($subject, $item);
            }

            return $filtered;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getEligibilityCoupons(DiscountSubjectContract $subject, $userId)
    {
        try {
            $coupons = $this->couponRepository->findActiveByUser($userId, false);
            if (0 == count($coupons)) {
                return false;
            }

            $filtered = $coupons->filter(function ($item) use ($subject) {
                return $this->couponChecker->isEligible($subject, $item);
            });

            if (0 == count($filtered)) {
                return false;
            }

            foreach ($filtered as $item) {
                $this->applicator->calculate($subject, $item);
            }

            return $filtered;
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkDiscount(DiscountSubjectContract $subject, Discount $discount)
    {
        return $this->discountChecker->isEligible($subject, $discount);
    }

    public function checkCoupon(DiscountSubjectContract $subject, Coupon $coupon)
    {
        return $this->couponChecker->isEligible($subject, $coupon);
    }

    public function getDiscountsByGoods(DiscountItemContract $discountItemContract)
    {
        $discounts = $this->discountRepository->findActive(2);
        $discounts = $discounts->filter(function ($item) use ($discountItemContract) {
            return $this->discountChecker->isEligibleItem($discountItemContract, $item);
        });

        return $discounts;
    }

    /**
     * 计算出优惠组合，把优惠的可能情况都计算出来给到前端
     *
     * @param $discounts
     * @param $coupons
     */
    public function getOrderDiscountGroup($order, $discounts, $coupons)
    {
        $order = Order::find($order->id);

        $groups = new Collection();

        $exclusiveDiscounts = $discounts->where('exclusive', 1);
        $exclusiveCoupons = $coupons->where('discount.exclusive', 1);

        $normalDiscounts = $discounts->where('exclusive', 0);
        $normalCoupons = $coupons->where('discount.exclusive', 0);

        $exclusiveDiscounts->each(function ($item, $key) use ($groups) {

            $groups->push(['discount' => $item->id, 'coupon' => 0]);
        });

        $exclusiveCoupons->each(function ($item, $key) use ($groups) {
            $groups->push(['discount' => 0, 'coupon' => $item->id]);
        });

        $normalDiscounts->each(function ($item, $key) use ($groups) {
            $groups->push(['discount' => $item->id, 'coupon' => 0]);
        });

        $normalCoupons->each(function ($item, $key) use ($groups) {
            $groups->push(['discount' => 0, 'coupon' => $item->id]);
        });

        foreach ($normalDiscounts as $discount) {
            foreach ($normalCoupons as $coupon) {
                $groups->push(['discount' => $discount->id, 'coupon' => $coupon->id]);
            }
        }

        $groups = $groups->unique();

        $result = new Collection();

        foreach ($groups as $group) {

            $discount = Discount::find($group['discount']);
            $coupon = Coupon::find($group['coupon']);

            list($discountAdjustment, $couponAdjustment, $adjustmentTotal) = $this->calculateDiscounts($order, $discount, $coupon);

            if ($adjustmentTotal == 0) {
                continue;
            }

            $group['discountAdjustment'] = $discountAdjustment;
            $group['couponAdjustment'] = $couponAdjustment;
            $group['adjustmentTotal'] = $adjustmentTotal;

            $result->push($group);
            //dd($group);
        }
        $result = $result->unique()->sortBy('adjustmentTotal');

        return collect_to_array($result);
    }

    public function calculateDiscounts($order, ...$discounts)
    {
        $adjustmentTotal = 0;
        $discountAdjustment = 0;
        $couponAdjustment = 0;

        $tempOrder = clone($order);

        foreach ($discounts as $discount) {
            if (is_null($discount)) {
                continue;
            }


            if ($discount->isCouponBased()) {
                if ($this->couponChecker->isEligible($tempOrder, $discount)) {
                    $this->applicator->calculate($tempOrder, $discount);
                    $adjustmentTotal = $adjustmentTotal + $discount->adjustmentTotal;
                    $couponAdjustment = $discount->adjustmentTotal;
                    $tempOrder->total = $tempOrder->total + $discount->adjustmentTotal;
                } else {
                    return 0;
                }

            } else {
                if ($this->discountChecker->isEligible($tempOrder, $discount)) {
                    $this->applicator->calculate($tempOrder, $discount);
                    $adjustmentTotal = $adjustmentTotal + $discount->adjustmentTotal;
                    $tempOrder->total = $tempOrder->total + $discount->adjustmentTotal;
                    $discountAdjustment = $discount->adjustmentTotal;
                }
            }
        }
        //dd($tempOrder);
        //\Log::info(json_encode($tempOrder));

        return [$discountAdjustment, $couponAdjustment, $adjustmentTotal];
    }
}
