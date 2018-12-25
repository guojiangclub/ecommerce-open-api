<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Core\Discount\Contracts;
use iBrand\Component\Discount\Contracts\DiscountContract;
use iBrand\Component\Discount\Contracts\DiscountSubjectContract;
use iBrand\Component\Discount\Contracts\RuleCheckerContract as BaseRuleCheckerContract;
/**
 * Interface RuleCheckerContract.
 */
interface RuleCheckerContract extends BaseRuleCheckerContract
{
    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return mixed
     */
    public function isEligible(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount);

    public function isEligibleByItem(DiscountItemContract $item, array $configuration);
}
