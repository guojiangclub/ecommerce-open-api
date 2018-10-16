<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Actions;

use iBrand\Component\Discount\Contracts\AdjustmentContract;
use iBrand\Component\Discount\Contracts\DiscountActionContract;
use iBrand\Component\Discount\Contracts\DiscountContract;

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
