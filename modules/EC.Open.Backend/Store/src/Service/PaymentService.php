<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 2018/1/19
 * Time: 21:00
 */

namespace iBrand\EC\Open\Backend\Store\Service;


use EasyWeChat\Factory;
use iBrand\EC\Open\Backend\Store\Model\MerchantPay;
use iBrand\EC\Open\Backend\Store\Model\MultiGrouponUsers;
use iBrand\EC\Open\Backend\Store\Processor\MultiGrouponRefundProcessor;
use Illuminate\Http\Request;

class PaymentService
{
    protected $config;

    protected $payment;
    protected $merchantPay;
    protected $multiGrouponRefundProcessor;

    public function __construct(MultiGrouponRefundProcessor $multiGrouponRefundProcessor)
    {
        $this->config = [
            'app_id' => settings('wechat_pay_app_id'),
            'mch_id' => settings('wechat_pay_merchant_id'),
            'key' => settings('wechat_pay_merchant_api_key'),
            'cert_path' => storage_path('share/apiclient_cert.pem'),
            'key_path' => storage_path('share/apiclient_key.pem')

            /* 'payment' => [
                 'merchant_id' => settings('wechat_pay_merchant_id'),
                 'key' => settings('wechat_pay_merchant_api_key'),
                 'cert_path' => storage_path('share/apiclient_cert.pem'),
                 'key_path' => storage_path('share/apiclient_key.pem')
             ],*/
        ];
        /*$this->payment = $app = Factory::officialAccount($this->config);
        $this->merchantPay = $this->payment->merchant_pay;*/

        $this->merchantPay = Factory::payment($this->config)->transfer;

        $this->multiGrouponRefundProcessor = $multiGrouponRefundProcessor;
    }

    public function pay($payData, $extra, $type = 'REFUND')
    {
        \Log::info('refund_payData:' . json_encode($payData));
        $merchantPayData = $payData;
        $merchantPayData['spbill_create_ip'] = request()->getClientIp();
        $result = $this->merchantPay->toBalance($merchantPayData);
        \Log::info('refund_pay_result:' . json_encode($result));
        if ($result['return_code'] == 'FAIL' AND $result['err_code'] != 'SYSTEMERROR') {
            return false;
        }
        \Log::info('refund_pay_result2:' . json_encode($result));

        MerchantPay::create([
            'origin_type' => $type,
            'origin_id' => $extra['origin_id'],
            'partner_trade_no' => $merchantPayData['partner_trade_no'],
            'payment_no' => isset($result['payment_no']) ? $result['payment_no'] : '',
            'channel' => $extra['channel'],
            'channel_id' => $extra['channel_id'],
            'amount' => $merchantPayData['amount'],
            'status' => $result['result_code'],
            'error_code' => isset($result['error_code']) ? $result['error_code'] : '',
            'err_code_des' => isset($result['err_code_des']) ? $result['err_code_des'] : '',
            'payment_time' => isset($result['payment_time']) ? $result['payment_time'] : null,
            'user_id' => $extra['user_id'],
            'admin_id' => $extra['admin_id']
        ]);

        if ($result['result_code'] == 'FAIL' AND $result['err_code'] == 'SYSTEMERROR') {
            $search = $result = $this->merchantPay->queryBalanceOrder($merchantPayData['partner_trade_no']);
            if ($search['return_code'] == 'SUCCESS'
                AND $search['result_code'] == 'SUCCESS'
                AND ($search['status'] == 'SUCCESS' OR $search['status'] == 'PROCESSING')
            ) {
                $result['result_code'] = 'SUCCESS';
            }
        }

        return $result;
    }

    public function multiGrouponRefund($groupon_item_id)
    {
        $users = MultiGrouponUsers::where('multi_groupon_items_id', $groupon_item_id)->where('status', 1)->get();
        if (count($users) > 0) {
            foreach ($users as $user) {
                if ($user->refund_status == 0) {
                    $this->multiGrouponRefundProcessor->refund($user);
                }
            }
        }
    }
}