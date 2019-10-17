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
use GuoJiangClub\Component\Discount\Models\Rule;

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
