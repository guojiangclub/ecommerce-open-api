<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Services;

use Exception;
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
}
