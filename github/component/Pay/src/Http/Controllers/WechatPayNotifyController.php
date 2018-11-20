<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Http\Controllers;

use Carbon\Carbon;
use iBrand\Component\Pay\Facades\PayNotify;
use Illuminate\Routing\Controller;
use Yansongda\Pay\Pay;

class WechatPayNotifyController extends Controller
{
    public function notify($app)
    {
        $config = config('ibrand.pay.default.wechat.'.$app);

        $pay = Pay::wechat($config);

        $data = $pay->verify(); // 是的，验签就这么简单！

        if ('SUCCESS' === $data['return_code']) { // return_code 表示通信状态，不代表支付状态
            $attach = json_decode($data['attach'], true);

            if ('SUCCESS' == $data['result_code']) {
                $charge = \iBrand\Component\Pay\Models\Charge::where('out_trade_no', $data['out_trade_no'])->first();

                if (!$pay) {
                    return response('支付失败', 500);
                }

                $charge->transaction_meta = json_encode($data);
                $charge->transaction_no = $data['transaction_id'];
                $charge->time_paid = Carbon::createFromTimestamp(strtotime($data['time_end']));
                $charge->paid = 1;
                $charge->save();

                if ($charge->amount !== $data['total_fee']) {
                    return response('支付失败', 500);
                }

                PayNotify::success($charge->type, $pay);

                return $pay->success();
            } elseif ('FAIL' == $data['result_code']) {
                $charge = \iBrand\Component\Pay\Models\Charge::where('out_trade_no', $data['out_trade_no'])->first();

                if ($charge) {
                    $charge->failure_code = $data['err_code'];
                    $charge->failure_msg = $data['err_code_des'];
                    $charge->save();
                }

                return response('支付失败', 500);
            }
        }

        return response('FAIL', 500);
    }
}
