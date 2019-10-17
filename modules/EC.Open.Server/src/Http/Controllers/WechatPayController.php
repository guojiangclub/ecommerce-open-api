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

use EasyWeChat;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use GuoJiangClub\Component\Payment\Models\Payment;
use Illuminate\Http\Request;
use iBrand\Component\Pay\Facades\Charge;

class WechatPayController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createCharge()
    {
        $user = request()->user();

        $order_no = request('order_no');

        if (!$order_no || !$order = $this->orderRepository->getOrderByNo($order_no)) {
            return $this->failed('订单不存在');
        }

        if ($user->cant('pay', $order)) {
            return $this->failed('无权操作此订单');
        }

        if (Order::STATUS_INVALID == $order->status) {
            return $this->failed('无法支付');
        }

        if (0 === $order->getNeedPayAmount()) {
            return $this->failed('无法支付，需支付金额为零');
        }

        $charge = Charge::create(['channel' => 'wx_lite'
            , 'order_no' => $order_no
            , 'amount' => $order->getNeedPayAmount()
            , 'client_ip' => \request()->getClientIp()
            , 'subject' => $order->getSubject()
            , 'body' => $order->getSubject()
            , 'extra' => ['openid' => \request('openid')]
        ]);

        return $this->success(compact('charge'));

        //openid 和 订单编号
        /*$payment = EasyWeChat::payment();

        $data = $payment->order->unify([
            'body' => $order->getSubject(),
            'out_trade_no' => $order_no,
            'total_fee' => $order->getNeedPayAmount(),
            'trade_type' => 'JSAPI',
            'openid' => \request('openid'),
            'notify_url' => url('api/wechat/notify', '', true),
        ]);

        if('FAIL' == $data['return_code']){
            return $this->failed($data['return_msg']);
        }

        //包装成前端直接可以用的参数
        if ('SUCCESS' == $data['return_code'] && 'SUCCESS' == $data['result_code']) {
            $jssdk = $payment->jssdk;
            $charge = $jssdk->sdkConfig($data['prepay_id']); // 返回数组
            $charge['timeStamp'] = $charge['timestamp'];
            return $this->success(compact('charge', 'data'));
        } elseif ('FAIL' == $data['result_code']) {
            return $this->failed($data['err_code_des']);
        }
        return $this->failed('支付未知错误');*/
    }
}
