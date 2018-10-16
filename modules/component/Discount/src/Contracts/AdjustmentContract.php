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
 * Interface AdjustmentContract.
 */
interface AdjustmentContract
{
    const ORDER_DISCOUNT_ADJUSTMENT = 'order_discount';

    const ORDER_ITEM_DISCOUNT_ADJUSTMENT = 'order_item_discount';

    /**
     * create a adjustment.
     *
     * @param $type
     * @param $label
     * @param $amount
     * @param $originId
     * @param $originType
     *
     * @return mixed
     */
    public function createNew($type, $label, $amount, $originId, $originType);
}
