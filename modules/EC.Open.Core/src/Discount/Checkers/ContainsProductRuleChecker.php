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

class ContainsProductRuleChecker implements RuleCheckerContract
{
    const TYPE = 'contains_product';

    public function isEligible(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount)
    {
        $flag = false;
        $validItems = new Collection();

        foreach ($subject->getItems() as $item) {
            if (isset($configuration['sku']) and !empty($configuration['sku']) and in_array($item->getItemKey(), explode(',', $configuration['sku']))) {
                $validItems->push($item);
            }
            if (isset($configuration['spu']) and !empty($configuration['spu']) and in_array($item->getItemKey('spu'), explode(',', $configuration['spu']))) {
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
        if ($cartQuantityRule = $discount->getRules()->where('type', CartQuantityRuleChecker::TYPE)->first()) {
            $count = $cartQuantityRule->getCartQuantity();
            if ($count > 0 and $count > $validItems->sum('quantity')) {
                $flag = false;
            }
        }

        if ($itemTotalRule = $discount->getRules()->where('type', ItemTotalRuleChecker::TYPE)->first()) {
            $amount = $itemTotalRule->getItemsTotal();
            if ($amount > 0 and $amount > $validItems->sum('units_total')) {
                $flag = false;
            }
        }

        return $flag;
    }

    public function isEligibleByItem(DiscountItemContract $item, array $configuration)
    {
        if ('goods' == $item->getItemType()) {
            if (isset($configuration['spu']) and !empty($configuration['spu']) and in_array($item->getKeyCode('spu'), explode(',', $configuration['spu']))) {
                return true;
            }
            $codes = $item->getChildKeyCodes();
            foreach ($codes as $code) {
                if (isset($configuration['sku']) and !empty($configuration['sku']) and in_array($code, explode(',', $configuration['sku']))) {
                    return true;
                }
            }
        }
        if ('product' == $item->getItemType()) {
            if ((isset($configuration['sku']) and !empty($configuration['sku']) and in_array($item->getKeyCode(), explode(',', $configuration['sku'])))
                or (isset($configuration['spu']) and !empty($configuration['spu']) and in_array($item->getKeyCode('spu'), explode(',', $configuration['spu'])))
            ) {
                return true;
            }
        }

        return false;
    }
}
