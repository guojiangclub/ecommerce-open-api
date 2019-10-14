<?php

namespace GuoJiangClub\EC\Open\Core\Processor;

use Carbon\Carbon;
use GuoJiangClub\Component\Order\Models\Order;

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

    public function submit($order)
    {
        if ($order->status == Order::STATUS_TEMP) {
            $order->status = Order::STATUS_NEW;
            $order->submit_time = Carbon::now();
            $order->save();
            event('order.submitted', [$order]);
        }
    }

}