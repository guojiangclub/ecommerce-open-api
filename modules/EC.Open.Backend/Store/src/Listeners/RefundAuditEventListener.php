<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/11/15
 * Time: 16:23
 */

namespace iBrand\EC\Open\Backend\Store\Listeners;


use iBrand\EC\Open\Backend\Store\Model\Order;
use iBrand\EC\Open\Backend\Store\Model\Refund;
use iBrand\EC\Open\Backend\Store\Model\RefundAmount;

class RefundAuditEventListener
{
    /**
     * 记录退款渠道，优先退现金
     * @param Refund $refund
     * @param $order_id
     */
    public function onRefundAgree(Refund $refund, $order_id)
    {
        $refunds = Refund::where('order_id', $order_id)
            ->where('id', '<>', $refund->id)
            ->whereNotIn('status', [0, 2, 4])
            ->get();

        $order = Order::find($order_id);
        $orderTotal = $order->total * 100;
        $refundAmount = $refund->amount * 100;

        if (count($refunds) > 0) {
            $cash = 0;
            $balance = 0;
            foreach ($refunds as $item) {
                $cash = $cash + $item->refundAmount->where('type', 'cash')->sum('amount');  //已记录退款现金
                $balance = $balance + $item->refundAmount->where('type', 'balance')->sum('amount'); //已记录退款余额
            }

            if ($balance > 0) {
                RefundAmount::create([
                    'amount' => $refundAmount,
                    'refund_id' => $refund->id,
                    'order_id' => $order_id,
                    'type' => 'balance'
                ]);
            } else {
                $realCash = $orderTotal - $order->balance_paid;  //订单实际使用现金
                $remained = $realCash - $cash;  //未退的现金
                if ($remained > 0 AND $remained >= $refundAmount) {  //如果还有现金未退完,并且剩余的现金大于申请的退款金额
                    RefundAmount::create([
                        'amount' => $refundAmount,
                        'refund_id' => $refund->id,
                        'order_id' => $order_id,
                        'type' => 'cash'
                    ]);
                } elseif ($remained > 0 AND $remained < $refundAmount) {
                    $data = [
                        ['amount' => $refundAmount - $remained, 'order_id' => $order_id, 'type' => 'balance'],
                        ['amount' => $remained, 'order_id' => $order_id, 'type' => 'cash']
                    ];
                    $refund->refundAmount()->createMany($data);
                } else {
                    RefundAmount::create([
                        'amount' => $refundAmount,
                        'refund_id' => $refund->id,
                        'order_id' => $order_id,
                        'type' => 'balance'
                    ]);
                }
            }
        } else {
            $balance = $refundAmount - ($orderTotal - $order->balance_paid); //需使用余额退款的金额=退款金额-订单现金支付金额
            if ($balance > 0) {
                $data[] = ['amount' => $balance, 'order_id' => $order_id, 'type' => 'balance'];
                $cash = $refundAmount - $balance;
                if ($cash > 0) {
                    $data[] = ['amount' => $refundAmount - $balance, 'order_id' => $order_id, 'type' => 'cash'];
                }

                $refund->refundAmount()->createMany($data);

            } else {
                RefundAmount::create([
                    'amount' => $refundAmount,
                    'refund_id' => $refund->id,
                    'order_id' => $order_id,
                    'type' => 'cash'
                ]);
            }
        }

    }

    public function subscribe($events)
    {
        $events->listen(
            'refund.agree',
            'iBrand\EC\Open\Backend\Store\Listeners\RefundAuditEventListener@onRefundAgree'
        );
    }
}