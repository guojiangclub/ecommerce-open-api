<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Checkers;

use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use GuoJiangClub\Component\Discount\Models\Discount;

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
