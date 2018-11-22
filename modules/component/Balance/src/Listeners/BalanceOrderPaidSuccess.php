<?php

/*
 * This file is part of ibrand/balance.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Balance\Listeners;

use Carbon\Carbon;
use iBrand\Component\Balance\Balance;
use iBrand\Component\Balance\BalanceOrder;

class BalanceOrderPaidSuccess
{
    public function balanceOrderPaidSuccess($order)
    {
        $balanceOrder = BalanceOrder::where('order_no', $order->order_no)->first();

        if ($balanceOrder) {
            $balanceOrder->pay_status = BalanceOrder::STATUS_PAY;
            $balanceOrder->pay_time = Carbon::now();
            $balanceOrder->save();

            $balance_data = [
                'user_id' => $balanceOrder->user_id,
                'type' => Balance::TYPE_RECHARGE,
                'value' => $balanceOrder->amount,
                'origin_id' => $balanceOrder->id,
                'origin_type' => get_class($balanceOrder),
            ];
            Balance::create($balance_data);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'balance.order.paid.success',
            'iBrand\Component\Balance\Listeners\BalanceOrderPaidSuccess@balanceOrderPaidSuccess'
        );
    }
}
