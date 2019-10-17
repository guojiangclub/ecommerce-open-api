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
use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;

class DiscountEligibilityChecker
{
    protected $datesEligibilityChecker;

    protected $usageLimitEligibilityChecker;

    protected $rulesEligibilityChecker;

    public function __construct(
        DatesEligibilityChecker $datesEligibilityChecker,
        UsageLimitEligibilityChecker $usageLimitEligibilityChecker,
        RulesEligibilityChecker $rulesEligibilityChecker
    ) {
        $this->datesEligibilityChecker = $datesEligibilityChecker;
        $this->usageLimitEligibilityChecker = $usageLimitEligibilityChecker;
        $this->rulesEligibilityChecker = $rulesEligibilityChecker;
    }

    public function isEligible(DiscountSubjectContract $subject, DiscountContract $discount)
    {
        if (!$this->datesEligibilityChecker->isEligible($discount)) {
            return false;
        }

        if (!$this->usageLimitEligibilityChecker->isEligible($discount)) {
            return false;
        }

        $eligible = $this->rulesEligibilityChecker->isEligible($subject, $discount);

        if (!$eligible) {
            return false;
        }

        return $eligible;
    }
}
