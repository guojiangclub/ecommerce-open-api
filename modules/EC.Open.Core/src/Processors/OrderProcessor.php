<?php

namespace iBrand\EC\Open\Core\Processor;

use Carbon\Carbon;
use iBrand\Component\Order\Models\Order;

class OrderProcessor
{
    /**
     * cancel order.
     * @param Order $order
     * @param string $cancelReason
     * @return bool
     */
    public function cancel(Order $order, $cancelReason = '用户取消')
    {
        if ($order->status == Order::STATUS_NEW) {
            $order->status = Order::STATUS_CANCEL;
            $order->completion_time = Carbon::now();
            $order->cancel_reason = $cancelReason;
            $order->save();
            event('order.canceled', [$order]);
            return true;
        }
        return false;
    }

}