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
use iBrand\Component\Discount\Contracts\RuleCheckerContract;

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
