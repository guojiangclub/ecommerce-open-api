<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use Carbon\Carbon;
use ElementVip\Component\Card\Models\Card;
use ElementVip\Component\User\Models\UserBind;
use iBrand\EC\Open\Backend\Store\Model\MerchantPay;
use iBrand\EC\Open\Backend\Store\Model\ShippingMethod;
use iBrand\EC\Open\Backend\Store\Service\PaymentService;
use Illuminate\Http\Request;
use iBrand\EC\Open\Backend\Store\Repositories\RefundRepository;
use iBrand\Backend\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Service\RefundService;
use DB;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class RefundController extends Controller
{
    protected $refundRepository;
    protected $orderRepository;
    protected $freightCompanyRepository;
    protected $refundService;
    protected $paymentService;

    public function __construct(RefundRepository $refundRepository,
                                RefundService $refundService,
                                PaymentService $paymentService
    )
    {
        $this->refundRepository = $refundRepository;
        $this->refundService = $refundService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = request('status');
        $where['channel'] = 'ec';
        if (empty($view)) {
            $view = 0;
        }

        $value = '';
        if (!empty(request('value'))) {
            if (request('field') == 'order_no') {
                $value = request('value');
            } else {
                $where['refund_no'] = ['like', '%' . request('value') . '%'];
            }
        }

        $refunds = $this->refundRepository->getRefundsPaginated($view, $where, $value);

        return LaravelAdmin::content(function (Content $content) use ($refunds) {

            $content->header('售后列表');

            $content->breadcrumb(
                ['text' => '售后管理', 'url' => 'store/refund', 'no-pjax' => 1],
                ['text' => '售后列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '售后管理']

            );

            $content->body(view('store-backend::refund.index', compact('refunds')));
        });

//        return view('store-backend::refund.index', compact('refunds'));
    }

    public function show($id)
    {
        $refund = $this->refundRepository->find($id);
        $freight = ShippingMethod::all();

        $treasurer = false;
        $roles = auth()->guard('admin')->user()->roles;
        $filtered = $roles->filter(function ($value, $key) {
            return $value->slug == 'finance';
        });
        if (count($filtered) > 0) {
            $treasurer = true;
        }

        $refundAmount = true;
        if (!$refund->refundAmount->where('type', 'cash')->first()) {
            $refundAmount = false;
        }

        $action = '';
        if ($refund->status == 6 AND $refund->logs->last()->action == 'reject') {
            //如果收到用户退货，但是需要拒绝退款，定义action
            $action = 'reject';
        }

        return LaravelAdmin::content(function (Content $content) use ($refund, $freight, $treasurer, $refundAmount, $action) {

            $content->header('售后详情');

            $content->breadcrumb(
                ['text' => '售后管理', 'url' => 'store/refund', 'no-pjax' => 1],
                ['text' => '售后详情', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '售后管理']

            );

            $content->body(view('store-backend::refund.show', compact('refund', 'freight', 'treasurer', 'refundAmount', 'action')));
        });

//        return view('store-backend::refund.show', compact('refund', 'freight', 'treasurer', 'refundAmount', 'action'));

    }

    /**
     * 申请处理
     */
    public function store()
    {
        $status = request('status');
        $type = request('type');
        $id = request('id');
        $uid = auth()->guard('admin')->user()->id;

        $refund = $this->refundRepository->find($id);

        try {
            DB::beginTransaction();
            if ($status == 0)    //审核处理
            {
                $opinion = request('opinion');
                $typeText = request('typeText');
                $remarks = request('remarks');
                $this->refundService->handleRefund($id, $opinion, $uid, $remarks);
            }

            if ($status == 6)//用户已发货
            {
                $remarks = request('remarks');
                if (request('action') == 'reject') { //拒绝退款处理
                    $this->refundService->reject($id, $uid, $remarks, request('log_action'));
                } else { //商家确认收货处理
                    $this->refundService->confirmReceipt($type, $id, $uid, $remarks);
                }
            }

            if ($status == 7)    //商家发货处理
            {
                $express = request('express');
                $number = request('number');
                $this->refundService->deliverGoods($express, $number, $id, $uid);
            }

            if ($status == 5)    //商家代替用户填写退货物流信息
            {
                $express = request('express');
                $number = request('number');
                $this->refundService->deliverForUser($express, $number, $id, $uid);
            }


            /*if ($status == 8)  //商家打款处理
            {
                $remarks = request('remarks');
                $this->refundService->handlePaid($id, $uid, $remarks);
            }*/

            DB::commit();

            return response()->json(['status' => true
                , 'error_code' => 0
                , 'error' => ''
                , 'data' => $refund->id
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);
            return $this->ajaxJson(false, [], 404, '提交失败');
        }


    }

    /**
     * 商家打款处理
     * @return mixed
     */
    public function paid()
    {
        $remarks = request('remarks');
        $channel = request('channel');
        $id = request('id');
        $uid = auth()->guard('admin')->user()->id;

        $refund = $this->refundRepository->find($id);
        if ($refund->status != 8) {
            return $this->ajaxJson(false, [], 404, '提交失败');
        }

        $paid_time = Carbon::now();

        if (settings('enabled_pingxx_pay')) {
            if (!$channel AND request('refundAmount')) {
                return $this->ajaxJson(false, [], 404, '请选择退款渠道');
            }
            if ($channel == 'wechat') {
                if (!$refundAmount = $refund->refundAmount->where('type', 'cash')->first()) {
                    return $this->ajaxJson(false, [], 404, '该售后不存在现金退款');
                }

                if (MerchantPay::where('origin_type', 'REFUND')
                    ->where('origin_id', $refund->id)
                    ->where('channel', 'wechat')
                    ->where('channel_id', $refundAmount->id)
                    ->where('status', 'SUCCESS')->first()
                ) {
                    return $this->ajaxJson(false, [], 404, '该售后已退款');
                }


                $userBind = UserBind::ByAppID($refund->user_id, 'wechat', settings('wechat_pay_app_id'))->first();
                if (!$userBind) {
                    return $this->ajaxJson(false, [], 404, '无法匹配微信用户,请切换打款方式');
                }

                if (!Storage::disk('share')->exists('apiclient_cert.pem') OR !Storage::disk('share')->exists('apiclient_key.pem')) {
                    return $this->ajaxJson(false, [], 404, '微信支付证书文件不存在');
                }

                $merchantPayData = [
                    'partner_trade_no' => build_order_no('MPR'),
                    'openid' => $userBind->open_id, //收款人的openid
                    'check_name' => 'NO_CHECK',
                    'amount' => $refundAmount->amount,
                    'desc' => '订单：' . $refund->order->order_no . '售后退款'
                ];
                $extra = [
                    'origin_id' => $refund->id,
                    'channel' => $channel,
                    'channel_id' => $refundAmount->id,
                    'user_id' => $refund->user_id,
                    'admin_id' => $uid
                ];

                $result = $this->paymentService->pay($merchantPayData, $extra);
                if (!$result) {
                    return $this->ajaxJson(false, [], 404, '打款失败，请重试');
                }

                if ($result['result_code'] == 'FAIL') {
                    return $this->ajaxJson(false, [], 404, $result['err_code_des']);
                }
                $paid_time = $result['payment_time'];

            }
        }

        $this->refundService->handlePaid($id, $uid, $remarks, $paid_time);

        return $this->ajaxJson();

    }

    /**
     * 主动修改状态
     * @param $id
     * @return mixed
     */
    public function getStatus($id)
    {
        $refund = $this->refundRepository->find($id);

        return view('store-backend::refund.get-status', compact('refund'));
    }

    public function changeStatus()
    {
        $refund = $this->refundRepository->find(request('id'));
        if ($refund) {
            $uid = auth()->guard('admin')->user()->id;

            $refund->status = request('status');
            $refund->save();

            $this->refundService->refundLog(request('id'), 0, $uid, 'adminAction', request('remark'));

            return $this->ajaxJson();
        }
        return $this->ajaxJson(false, [], 403, '修改失败');

    }

    /**
     * 获取需要导出的数据
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 20;
        $data = $this->refundService->getExportData($page, $limit);
        $lastPage = $data['lastPage'];
        $cacheName = $data['cacheName'];

        if ($page == $lastPage OR $lastPage == 0) {
            $title = ['提交日期', '工单号', '售后类型', '退货原因', '退款金额', '工单状态', '完成时间', '退货商品', '对应订单', '用户退货说明'];
            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => 'xsl', 'title' => $title, 'cache' => $cacheName, 'prefix' => 'refunds_data_']);
        } else {
            $url_bit = route('admin.refund.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));
            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
        }
    }


}
