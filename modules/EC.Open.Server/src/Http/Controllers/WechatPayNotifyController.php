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
use GuoJiangClub\Component\Payment\Services\PaymentService;

class WechatPayNotifyController extends Controller
{
    private $payment;

    public function __construct(PaymentService $paymentService)
    {
        $this->payment = $paymentService;
    }

    protected function notify()
    {
        $payment = EasyWeChat::payment();

        $response = $payment->handlePaidNotify(function ($message, $fail) {
            if ('SUCCESS' === $message['return_code']) { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ('SUCCESS' === array_get($message, 'result_code')) {
                    $charge['metadata']['order_no'] = $message['out_trade_no'];
                    $charge['amount'] = $message['total_fee'];
                    $charge['transaction_no'] = $message['transaction_id'];
                    $charge['time_paid'] = strtotime($message['time_end']);
                    $charge['details'] = json_encode($message);
                    $charge['channel'] = 'wx_lite';

                    $this->payment->paySuccess($charge);

                    return true; // 返回处理完成

                    // 用户支付失败
                } elseif ('FAIL' === array_get($message, 'result_code')) {
                    return $fail('支付失败');
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return $fail('支付失败');
        });

        return $response;
    }
}
