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
use iBrand\Component\Discount\Contracts\DiscountSubjectContract;
use iBrand\Component\Discount\Models\Rule;

class RulesEligibilityChecker
{
    public function isEligible(DiscountSubjectContract $subject, DiscountContract $discount)
    {
        if (!$discount->hasRules()) {
            return true;
        }

        foreach ($discount->getRules() as $rule) {
            if (!$this->isEligibleToRule($subject, $rule, $discount)) {
                return false;
            }
        }

        return true;
    }

    protected function isEligibleToRule(DiscountSubjectContract $subject, Rule $rule, DiscountContract $discountContract)
    {
        $checker = app($rule->type);

        $configuration = json_decode($rule->configuration, true);

        return $checker->isEligible($subject, $configuration, $discountContract);
    }
}
