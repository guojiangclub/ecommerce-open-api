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
use GuoJiangClub\Component\Discount\Contracts\RuleCheckerContract;

/**
 * Class CartQuantityRuleChecker.
 */
class CartQuantityRuleChecker implements RuleCheckerContract
{
    const TYPE = 'cart_quantity';

    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return bool|mixed
     */
    public function isEligible(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount)
    {
        return $subject->getSubjectCount() >= $configuration['count'];
    }
}
