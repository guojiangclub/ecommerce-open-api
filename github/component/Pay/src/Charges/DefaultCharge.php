<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Charges;

use Carbon\Carbon;
use iBrand\Component\Pay\Contracts\PayChargeContract;
use iBrand\Component\Pay\Exceptions\GatewayException;
use iBrand\Component\Pay\Models\Charge;
use Yansongda\Pay\Pay;

class DefaultCharge extends BaseCharge implements PayChargeContract
{
    /**
     * 创建支付请求
     *
     * @param array  $data 支付数据
     * @param string $type 业务类型
     * @param string $app  支付参数APP，config payments 数组中的配置项名称
     *
     * @return mixed
     *
     * @throws GatewayException
     */
    public function create(array $data, $type = 'default', $app = 'default')
    {
        $this->validateParams($data);

        if (!in_array($data['channel'], ['wx_pub', 'wx_pub_qr', 'wx_lite', 'alipay_wap', 'alipay_pc_direct'])) {
            throw new \InvalidArgumentException("Unsupported channel [{$data['channel']}]");
        }

        $modelData = array_merge(['app' => $app, 'type' => $type], array_only($data, ['channel', 'order_no', 'client_ip', 'subject', 'amount',
            'body', 'extra', 'time_expire', 'metadata', 'description', ]));

        $payModel = Charge::create($modelData);

        try {
            $credential = null;
            $out_trade_no = null;

            $config = config('ibrand.pay.default.wechat.'.$app);

            switch ($data['channel']) {
                case 'wx_pub':
                case 'wx_pub_qr':
                case 'wx_lite':
                    $config['notify_url'] = route('pay.wechat.notify', ['app' => $app]);
                    $credential = $this->createWechatCharge($data, $config, $out_trade_no);
                    break;
                case 'alipay_wap':
                case 'alipay_pc_direct':
                    /*return $this->createAliCharge($user_id, $channel, $type, $order_no, $amount, $subject, $body, $ip, $openid, $extra, $submit_time);*/
            }

            $payModel->credential = $credential;
            $payModel->out_trade_no = $out_trade_no;
            $payModel->save();

            return $payModel;
        } catch (\Yansongda\Pay\Exceptions\Exception $exception) {
            throw  new GatewayException('支付通道错误');
        } catch (\Exception $exception) {
            throw  new GatewayException('支付失败');
        }
    }

    /**
     * @param $data
     * @param $config
     *
     * @return array|null
     */
    protected function createWechatCharge($data, $config, &$out_trade_no)
    {
        $out_trade_no = $this->getWxPayCode($data['order_no'], $data['channel']);

        $chargeData = [
            'body' => mb_strcut($data['body'], 0, 32, 'UTF-8'),
            'out_trade_no' => $out_trade_no,
            'total_fee' => abs($data['amount']),
            'spbill_create_ip' => $data['client_ip'],
        ];

        if (isset($data['time_expire'])) {
            $chargeData['time_expire'] = $data['time_expire'];
        }

        if (isset($data['metadata'])) {
            $chargeData['attach'] = json_encode($data['metadata']);
        }

        switch ($data['channel']) {
            case 'wx_pub_qr':
                $pay = Pay::wechat($config)->scan($chargeData);

                return ['wechat' => $pay];
            case 'wx_pub':
                $chargeData['openid'] = $data['extra']['openid'];
                $pay = Pay::wechat($config)->mp($chargeData);

                return ['wechat' => $pay];

            case 'wx_lite':
                $chargeData['openid'] = $data['extra']['openid'];
                $pay = Pay::wechat($config)->miniapp($chargeData);

                return ['wechat' => $pay];
            default:
                return null;
        }
    }

    protected function createAliCharge($user_id, $channel, $type = 'order', $order_no, $amount, $subject, $body, $ip = '127.0.0.1', $openid = '', $extra = [], $submit_time = '')
    {
        $config = $this->getConfig('alipay');

        $delayTime = app('system_setting')->getSetting('order_auto_cancel_time') ? app('system_setting')->getSetting('order_auto_cancel_time') : 1440;
        $time_expire = $delayTime.'m';
        if ($submit_time and ($gap = Carbon::now()->timestamp - strtotime($submit_time)) > 0) {
            $time_expire = ($delayTime - floor($gap / 60)).'m';
        }

        $extra = $this->createExtra($channel, '', $extra, $type);

        $amount = $amount / 100;

        $chargeData = [
            'body' => mb_strcut($body, 0, 32, 'UTF-8'),
            'out_trade_no' => $order_no,
            'total_amount' => number_format($amount, 2, '.', ''),
            'subject' => mb_strcut($subject, 0, 32, 'UTF-8'),
            'client_ip' => $ip,
            'timeout_express' => $time_expire,
            'passback_params' => json_encode(['user_id' => $user_id, 'order_sn' => $order_no, 'type' => $type, 'channel' => $channel]),
        ];

        if (!empty($extra['cancel_url'])) {
            $chargeData['quit_url'] = $extra['cancel_url'];
        }

        $return_url = $extra['success_url'].$order_no;
        $return_url = str_replace('/', '~', $return_url);
        $return_url = str_replace('?', '@', $return_url);
        $return_url = str_replace('#', '*', $return_url);

        $config['return_url'] = $config['return_url'].'/'.$return_url; //同步通知url
        $config['notify_url'] = $config['notify_url']; //异步通知url

        $ali_pay = [];
        if ('alipay_pc_direct' == $channel) {
            //unset($chargeData['passback_params']);
            $ali_pay = Pay::alipay($config)->web($chargeData);
            $key = base64_encode($order_no);
            \Cache::put($order_no, html_entity_decode($ali_pay), 1);
            $this->createPaymentLog('create_charge', Carbon::now(), $order_no, $chargeData['out_trade_no'], '', $amount * 100, $channel, $type, 'SUCCESS', $user_id, $ali_pay);

            return [
                'type' => $type,
                'order_no' => $order_no,
                'channel' => $channel,
                'pay_scene' => 'live',
                'key' => $key,
            ];
        }

        if ('alipay_wap' == $channel) {
            $ali_pay = Pay::alipay($config)->wap($chargeData);
            $this->createPaymentLog('create_charge', Carbon::now(), $order_no, $chargeData['out_trade_no'], '', $amount * 100, $channel, $type, 'SUCCESS', $user_id, $ali_pay);

            return [
                'type' => $type,
                'order_no' => $order_no,
                'channel' => $channel,
                'pay_scene' => 'live',
                'form' => html_entity_decode($ali_pay),
            ];
        }

        return null;
    }

    public function find($charge_id)
    {
        $charge = Charge::where('charge_id',$charge_id)->first();

        $config = config('ibrand.pay.default.wechat.'.$charge->app);

        $result = Pay::wechat($config)->find($charge->out_trade_no);

        if ($result['return_code'] == 'FAIL'){
            $charge->failure_code = $result['return_code'];
            $charge->failure_msg = $result['return_msg'];
            $charge->save();
            return $charge;
        };

        if ($result['result_code'] == 'FAIL' || $result['trade_state'] != 'SUCCESS') {
            $charge->failure_code = $result['err_code'];
            $charge->failure_msg = $result['err_code_des'];
            $charge->save();
        };

        $charge->transaction_meta = json_encode($result);
        $charge->transaction_no = $result['transaction_id'];
        $charge->time_paid = Carbon::createFromTimestamp(strtotime($result['time_end']));
        $charge->paid = 1;
        $charge->save();

        return $charge;
    }
}
