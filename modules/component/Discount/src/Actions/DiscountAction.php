<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Actions;

use GuoJiangClub\Component\Discount\Contracts\AdjustmentContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountActionContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountContract;

abstract class DiscountAction implements DiscountActionContract
{
    /**
     * @param DiscountContract $discount
     * @param $amount
     *
     * @return mixed
     */
    protected function createAdjustment(DiscountContract $discount, $amount)
    {
        $adjustment = app(AdjustmentContract::class);

        $originType = $discount->isCouponBased() ? 'coupon' : 'discount';

        return $adjustment->createNew(AdjustmentContract::ORDER_DISCOUNT_ADJUSTMENT, $discount->label, $amount, $discount->id, $originType);
    }
}
