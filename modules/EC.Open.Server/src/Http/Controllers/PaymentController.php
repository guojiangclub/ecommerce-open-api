<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use Carbon\Carbon;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use iBrand\Component\Pay\Facades\Charge;
use iBrand\Component\Pay\Facades\PayNotify;
use GuoJiangClub\Component\Payment\Models\Payment;
use GuoJiangClub\Component\Payment\Services\PaymentService;
use EasyWeChat;

class PaymentController extends Controller
{
    private $payment;
    private $orderRepository;

    public function __construct(PaymentService $paymentService,
                                OrderRepository $orderRepository
    )
    {
        $this->payment = $paymentService;
        $this->orderRepository = $orderRepository;
    }

    public function paidSuccess()
    {
        $user = request()->user();
        $order_no = request('order_no');

        if (!$order_no || !$order = $this->orderRepository->getOrderByNo($order_no)) {
            return $this->failed('订单不存在');
        }

        if ($user->cant('update', $order)) {
            return $this->failed('无权操作.');
        }

        //在pay_debug=true 状态下，可以调用此接口直接更改订单支付状态
        if (config('ibrand.app.pay_debug')) {

            $charge = \GuoJiangClub\Component\Pay\Models\Charge::where('order_no', $order_no)->orderBy('created_at', 'desc')->first();
            $charge->transaction_no = '';
            $charge->time_paid = Carbon::now();
            $charge->paid = 1;
            $charge->channel = 'test';
            $charge->amount = $order->total;
            $charge->save();

            $order = PayNotify::success($charge->type, $charge);

        } else {
            //同步查询微信订单状态，防止异步通信失败导致订单状态更新失败

            $charge = Charge::find(request('charge_id'));

            $order = PayNotify::success($charge->type, $charge);


            /*$payment = EasyWeChat::payment();
            $result = $payment->order->queryByOutTradeNumber($order_no);

            if ('FAIL' == $result['return_code']) {
                return $this->failed($result['return_msg']);
            }

            if ('FAIL' == $result['result_code']) {
                return $this->failed($result['err_code_des']);
            }

            if ('SUCCESS' != $result['trade_state']) {
                return $this->failed($result['trade_state_desc']);
            }

            $charge['metadata']['order_no'] = $result['out_trade_no'];
            $charge['amount'] = $result['total_fee'];
            $charge['transaction_no'] = $result['transaction_id'];
            $charge['time_paid'] = strtotime($result['time_end']);
            $charge['details'] = json_encode($result);
            $charge['channel'] = 'wx_lite';

            $order = $this->payment->paySuccess($charge);*/
        }

        if (Order::STATUS_PAY == $order->status) {
            return $this->success(['order' => $order, 'payment' => '微信支付']);
        }

        return $this->failed('支付失败');
    }
}
