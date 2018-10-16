<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Checkers;

use iBrand\Component\Discount\Contracts\DiscountSubjectContract;
use iBrand\Component\Discount\Models\Coupon;

/**
 * Class CouponEligibilityChecker.
 */
class CouponEligibilityChecker
{
    /**
     * @var RulesEligibilityChecker
     */
    protected $rulesEligibilityChecker;
    /**
     * @var DatesEligibilityChecker
     */
    protected $datesEligibilityChecker;

    /**
     * CouponEligibilityChecker constructor.
     *
     * @param RulesEligibilityChecker $rulesEligibilityChecker
     * @param DatesEligibilityChecker $datesEligibilityChecker
     */
    public function __construct(
        RulesEligibilityChecker $rulesEligibilityChecker,
        DatesEligibilityChecker $datesEligibilityChecker
    ) {
        $this->rulesEligibilityChecker = $rulesEligibilityChecker;
        $this->datesEligibilityChecker = $datesEligibilityChecker;
    }

    /**
     * @param DiscountSubjectContract $subject
     * @param Coupon                  $coupon
     *
     * @return bool
     */
    public function isEligible(DiscountSubjectContract $subject, Coupon $coupon)
    {
        if (!$this->datesEligibilityChecker->isEligible($coupon)) {
            return false;
        }

        $eligible = $this->rulesEligibilityChecker->isEligible($subject, $coupon->discount);

        if (!$eligible) {
            return false;
        }

        return $eligible;
    }
}
