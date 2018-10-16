<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Applicators;

use iBrand\Component\Discount\Checkers\ItemTotalRuleChecker;
use iBrand\Component\Discount\Contracts\DiscountContract;
use iBrand\Component\Discount\Contracts\DiscountSubjectContract;

/**
 * Class DiscountApplicator.
 */
class DiscountApplicator
{
    /**
     * @param DiscountSubjectContract $subject
     * @param DiscountContract        $discount
     */
    public function apply(DiscountSubjectContract $subject, DiscountContract $discount)
    {
        if (count($discount->getActions())) {
            foreach ($discount->getActions() as $action) {
                $configuration = json_decode($action->configuration, true);

                app($action->type)->execute($subject, $configuration, $discount);

                $discount->setCouponUsed();
            }
        }
    }

    /**
     * calculate order discount.
     *
     * @param DiscountSubjectContract $subject
     * @param DiscountContract        $discount
     */
    public function calculate(DiscountSubjectContract $subject, DiscountContract $discount)
    {
        $discount->orderAmountLimit = 0;

        if (count($discount->getActions())) {
            foreach ($discount->getActions() as $action) {
                $configuration = json_decode($action->configuration, true);
                app($action->type)->calculate($subject, $configuration, $discount);
            }
        }

        foreach ($discount->getRules() as $rule) {
            if (ItemTotalRuleChecker::TYPE == $rule->type) {
                $discount->orderAmountLimit = json_decode($rule->configuration, true)['amount'];
            }
        }
    }
}
