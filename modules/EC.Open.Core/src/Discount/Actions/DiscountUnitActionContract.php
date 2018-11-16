<?php

/*
 * This file is part of ibrand/core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Core\Discount\Actions;

use iBrand\Component\Discount\Contracts\AdjustmentContract;
use iBrand\Component\Discount\Contracts\DiscountActionContract;
use iBrand\Component\Discount\Contracts\DiscountContract;
use iBrand\Component\Discount\Models\Rule;
use iBrand\Core\Discount\Checkers\ContainsCategoryRuleChecker;
use iBrand\Core\Discount\Checkers\ContainsProductRuleChecker;
use iBrand\Core\Discount\Contracts\DiscountItemContract;

/**
 * Class DiscountUnitActionContract
 * @package iBrand\Core\Discount\Actions
 */
abstract class DiscountUnitActionContract  implements DiscountActionContract
{
    /**
     * @param DiscountItemContract $subjectItem
     * @param DiscountContract $discount
     * @return bool
     */
    public function checkItemRule(DiscountItemContract $subjectItem, DiscountContract $discount)
    {
        if (!$discount->hasRules()) {
            return true;
        }

        foreach ($discount->getRules()->whereIn('type', [ContainsCategoryRuleChecker::TYPE, ContainsProductRuleChecker::TYPE]) as $rule) {
            if (!$this->isEligibleToRule($subjectItem, $rule)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param DiscountItemContract $subjectItem
     * @param Rule $rule
     * @return mixed
     */
    protected function isEligibleToRule(DiscountItemContract $subjectItem, Rule $rule)
    {
        $checker = app($rule->type);

        $configuration = json_decode($rule->configuration, true);

        return $checker->isEligibleByItem($subjectItem, $configuration);
    }

    /**
     * @param DiscountContract $discount
     * @param $amount
     * @return mixed
     */
    protected function createAdjustment(DiscountContract $discount, $amount)
    {
        $adjustment = app(AdjustmentContract::class);

        $originType =$discount->isCouponBased() ? 'coupon' : 'discount';

        return  $adjustment->createNew(AdjustmentContract::ORDER_ITEM_DISCOUNT_ADJUSTMENT, $discount->label, $amount, $discount->id, $originType);
    }
}
