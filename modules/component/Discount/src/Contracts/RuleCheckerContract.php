<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Contracts;

/**
 * Interface RuleCheckerContract.
 */
interface RuleCheckerContract
{
    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return mixed
     */
    public function isEligible(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount);
}
