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

use Carbon\Carbon;
use iBrand\Component\Discount\Contracts\DiscountContract;

class DatesEligibilityChecker
{
    /**
     * @param DiscountContract $discount
     *
     * @return bool
     */
    public function isEligible(DiscountContract $discount)
    {
        $now = Carbon::now();

        $startsAt = $discount->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $discount->getEndsAt();
        if (null !== $endsAt && $now > $endsAt) {
            return false;
        }

        return true;
    }
}
