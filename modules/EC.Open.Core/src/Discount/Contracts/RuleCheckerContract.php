<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Discount\Contracts;
use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;
use GuoJiangClub\Component\Discount\Contracts\RuleCheckerContract as BaseRuleCheckerContract;
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
