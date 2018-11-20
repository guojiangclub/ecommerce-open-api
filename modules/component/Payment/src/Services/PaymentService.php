<?php

/*
 * This file is part of ibrand/payment.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Payment\Services;

use Carbon\Carbon;
use iBrand\Component\Order\Models\Order;
use iBrand\Component\Order\Repositories\OrderRepository;
use iBrand\Component\Pay\Contracts\PayNotifyContract;
use iBrand\Component\Pay\Models\Charge;
use iBrand\Component\Payment\Models\Payment;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/7
 * Time: 17:52.
 */
class PaymentService implements PayNotifyContract
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function success(Charge $charge)
    {
        $order = $this->orderRepository->getOrderByNo($charge->order_no);

        $need_pay = $order->getNeedPayAmount();

        $pay_state = $charge->amount - $need_pay;

        $order_pay = Payment::where('channel_no', $charge['transaction_no'])->where('order_id', $order->id)->first();

        if ($order_pay && 'completed' == $order_pay->status && 'balance' != $order_pay->channel) {
            return $order;
        }

        if ($pay_state >= 0) {
            $order = $this->orderRepository->getOrderByNo($charge->order_no);

            $payment = new Payment(['order_id' => $order->id, 'channel' => $charge['channel'],
                'amount' => $charge['amount'], 'status' => Payment::STATUS_COMPLETED, 'channel_no' =>
                    $charge['transaction_no'], 'paid_at' => $charge['time_paid'], 'details' => isset($charge['details']) ? $charge['details'] : '', ]);

            $order->payments()->save($payment);

            $order->status = Order::STATUS_PAY;
            $order->pay_time = Carbon::now();
            $order->pay_status = 1;
            $order->save();
        }

        return $order;
    }
}
