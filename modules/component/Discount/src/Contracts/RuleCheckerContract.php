<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Contracts;

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
