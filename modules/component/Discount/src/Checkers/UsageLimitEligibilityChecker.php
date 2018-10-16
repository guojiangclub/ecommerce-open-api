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

use iBrand\Component\Discount\Contracts\DiscountContract;
use iBrand\Component\Discount\Models\Discount;

class UsageLimitEligibilityChecker
{
    /**
     * @param Discount $discount
     *
     * @return bool
     */
    public function isEligible(DiscountContract $discount)
    {
        if (null === $usageLimit = $discount->getUsageLimit()) {
            return true;
        }

        if ($discount->getUsed() < $usageLimit) {
            return true;
        }

        return false;
    }
}
