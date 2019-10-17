<?php

/*
 * This file is part of ibrand/core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Discount\Actions;

use GuoJiangClub\Component\Discount\Contracts\AdjustmentContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountActionContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use GuoJiangClub\Component\Discount\Models\Rule;
use GuoJiangClub\EC\Open\Core\Discount\Checkers\ContainsCategoryRuleChecker;
use GuoJiangClub\EC\Open\Core\Discount\Checkers\ContainsProductRuleChecker;
use GuoJiangClub\EC\Open\Core\Discount\Contracts\DiscountItemContract;

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
