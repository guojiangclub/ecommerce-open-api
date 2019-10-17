<?php

/*
 * This file is part of ibrand/core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Discount\Checkers;

use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;
use GuoJiangClub\EC\Open\Core\Discount\Contracts\DiscountItemContract;
use GuoJiangClub\EC\Open\Core\Discount\Contracts\RuleCheckerContract;
use Illuminate\Support\Collection;

class ContainsCategoryRuleChecker implements RuleCheckerContract
{
    const TYPE = 'contains_category';

    public function isEligible(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount)
    {
        $flag = false;
        $validItems = new Collection();

        foreach ($subject->getItems() as $item) {
            if (isset($configuration['exclude_spu']) and in_array($item->getItemKey('spu'), explode(',', $configuration['exclude_spu']))) {
                continue;
            }

            $ids = $item->getModel()->getCategories()->pluck('id')->intersect($configuration['items']);
            if ($ids and $ids->count() > 0) {
                $validItems->push($item);
            }
        }

        if ($validItems->count() > 0) {
            //1. 说明只要有一件商品满足规则，就当满足条件
            $flag = true;
        } else {
            return false;
        }

        //2. 其他条件检查

        /*if ($cartQuantityRule = $discount->getRules()->where('type', CartQuantityRuleChecker::TYPE)->first()) {
            $count = $cartQuantityRule->getCartQuantity();
            if ( $count > 0 AND $count > $validItems->sum('quantity')) {
                $flag = false;
            }
        }

        if ($itemTotalRule = $discount->getRules()->where('type', ItemTotalRuleChecker::TYPE)->first()) {
            $amount = $itemTotalRule->getItemsTotal();
            if ($amount  > 0 AND $amount > $validItems->sum('units_total')) {
                $flag = false;
            }
        }*/

        return $flag;
    }

    public function isEligibleByItem(DiscountItemContract $item, array $configuration)
    {
        $ids = $item->getCategories()->pluck('id')->intersect($configuration['items']);
        if ($ids and $ids->count() > 0) {
            return true;
        }

        return false;
    }
}
