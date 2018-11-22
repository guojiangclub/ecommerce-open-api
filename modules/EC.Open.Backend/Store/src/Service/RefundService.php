<?php

namespace iBrand\EC\Open\Backend\Store\Service;

use Carbon\Carbon;
use ElementVip\Component\Payment\Models\PaymentLog;
use ElementVip\Component\Refund\Models\Refund;
use iBrand\EC\Open\Backend\Store\Model\OrderItem;
use iBrand\EC\Open\Backend\Store\Model\RefundLog;
use iBrand\EC\Open\Backend\Store\Model\RefundShipping;
use iBrand\EC\Open\Backend\Store\Model\Shipping;
use iBrand\EC\Open\Backend\Store\Model\ShippingMethod;
use iBrand\EC\Open\Backend\Store\Repositories\OrderRepository;
use iBrand\EC\Open\Backend\Store\Repositories\RefundRepository;

class RefundService
{
    protected $orderRepository;
    protected $refundRepository;
    protected $refundService;
    protected $cache;
    protected $paymentRefund;

    public function __construct(\ElementVip\Component\Refund\Service\RefundService $refundService,
                                \ElementVip\Component\Payment\Services\RefundService $paymentRefundService)
    {
        $this->refundRepository = app(RefundRepository::class);
        $this->orderRepository = app(OrderRepository::class);
        $this->refundService = $refundService;
        $this->cache = cache();
        $this->paymentRefund = $paymentRefundService;
    }

    /**
     * 商家发货处理
     *
     * @param $express  快递公司
     * @param $number   快递号
     * @param $id       申请ID
     */
    public function deliverGoods($express, $number, $id, $uid)
    {
        $refund = $this->refundRepository->find($id);

        $refund->status = 3;    //商城已发货,申请完成
        $refund->save();
        $this->refundService->refundLog($refund->id, 0, $uid, 'send', '商城已发货', '物流公司：' . $express . '，  运单号：' . $number);
        event('refund.service.changed', [$refund, '商城已发货: 物流公司：' . $express . '，  运单号：' . $number]);
    }

    /**
     * 商家确认打款
     *
     * @param $id
     * @param $uid
     * @param $remarks
     */
    public function handlePaid($id, $uid, $remarks, $paid_time)
    {
        $refund = $this->refundRepository->find($id);

        if (!settings('enabled_pingxx_pay') AND $refundAmount = $refund->refundAmount->where('type', 'cash')->first()) {

            $result = $this->originalRefund($refund, $refundAmount);
            if (count($result) == 0) return;

            $this->paymentRefund->createPaymentRefundLog('create_refund', Carbon::now(), $result['refund_no'], $result['order_no'], $result['refund_id'], $result['amount'], $result['channel'], $result['type'], 'SUCCESS', $result['meta']);
        }

        $refund->status = 3;    //商城确认打款,申请完成
        $refund->paid_time = $paid_time;
        $refund->save();
        $this->refundService->refundLog($refund->id, 0, $uid, 'receipt', '已退款，本次申请已完成', $remarks);

        //自动退余额
        event('balance.refund', [$refund]);
        event('refund.service.changed', [$refund, '已退款，本次申请已完成']);
    }

    protected function originalRefund($refund, $refundAmount)
    {
        $order = $refund->order;

        $paymentLog = PaymentLog::where('order_no', $order->order_no)
            ->where(function ($query) {
                $query->where('action', 'result_pay')->orWhere('action', 'query_result_pay');
            })->get()->last();

        if (!$paymentLog) return [];

        $this->paymentRefund->createPaymentRefundLog('apply_refund', Carbon::now(), $refund->refund_no, $order->order_no, '', $refundAmount->amount, $paymentLog->channel, 'order', 'SUCCESS', []);

        $description = '订单：' . $refund->order->order_no . '售后退款';
        $result = $this->paymentRefund->createRefund($order->order_no, $paymentLog->transcation_order_no, $refund->refund_no, $paymentLog->amount, $refundAmount->amount, $paymentLog->channel, $description);
        return $result;

    }

    /**
     * 如果部分发货，所有售后已完成，更改订单状态为已发货
     * @param $order_id
     */
    protected function changeOrderStatus($order_id)
    {
        $order = $this->orderRepository->find($order_id);
        if ($order->status != 2) {
            return;
        }
        $sendItem = $order->items()->where('is_send', 1)->get();
        $sendItemNum = count($sendItem);

        $unSendItem = $order->items()->where('is_send', 0)->get();
        $unSendItemNum = $unSendItem->sum('quantity');

        $filtered = $order->refunds->filter(function ($item) {  //未发货的item已完成的售后
            return $item->orderItem->is_send == 0 AND ($item->status == 8 OR $item->status == 3);
        });
        $filteredNum = $filtered->sum('quantity');

        if ($sendItemNum != 0 AND $unSendItemNum != 0 AND $unSendItemNum == $filteredNum) {
            $shipping = $order->shipping->last();
            $order->distribution_status = 1;
            $order->status = 3;
            $order->send_time = $shipping->delivery_time;
            $order->distribution = $shipping->id;
            $order->save();
        }
    }


    /**
     * 商家代理用户填写物流
     *
     * @param $express  快递公司
     * @param $number   快递号
     * @param $id       申请ID
     */
    public function deliverForUser($express, $number, $id, $uid)
    {
        $refund = $this->refundRepository->find($id);

        $refund->status = 6;
        $refund->save();
        $this->refundService->refundLog($refund->id, 0, $uid, 'express', '（管理员代操作）用户已退货', '物流公司：' . $express . '，  运单号：' . $number);

        $shipping = ShippingMethod::where('name', $express)->first();
        RefundShipping::create(['refund_id' => $refund->id,
            'code' => $shipping->code,
            'shipping_name' => $express,
            'shipping_tracking' => $number]);
    }

    /**
     * 获取需要导出的数据
     */
    public function getExportData($page, $limit)
    {

        $status = request('refund_status');

        $time = [];
        if (!empty(request('stime')) && !empty(request('etime'))) {
            $time = [request('stime'), request('etime')];
        } elseif (!empty(request('stime'))) {
            $time = [request('stime'), Carbon::now()];
        } elseif (!empty(request('etime'))) {
            $time = ['1970-01-01 00:00:00', request('etime')];
        }

        /*已完成的，完成时间筛选*/
        $c_time = [];
        if ($status == 2) {
            if (!empty(request('c_stime')) && !empty(request('c_etime'))) {
                $c_time = [request('c_stime'), request('c_etime')];
            } elseif (!empty(request('c_stime'))) {
                $c_time = [request('c_stime'), Carbon::now()];
            } elseif (!empty(request('etime'))) {
                $c_time = ['1970-01-01 00:00:00', request('c_etime')];
            }
        }


        $where['channel'] = 'ec';
        $refunds = $this->refundRepository->getRefundsPaginated($status, $where, '', $time, $c_time, $limit);

        $lastPage = $refunds->lastPage();

        $refundExcelData = $this->formatToExcelData($refunds, $status);

        if ($page == 1) {
            session(['export_refunds_cache' => generate_export_cache_name('export_refunds_cache_')]);
        }
        $cacheName = session('export_refunds_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $refundExcelData), 300);
        } else {
            $this->cache->put($cacheName, $refundExcelData, 300);
        }

        return ['lastPage' => $lastPage, 'cacheName' => $cacheName];
    }

    protected function formatToExcelData($refunds, $status)
    {
        $data = [];
        if ($refunds AND count($refunds) > 0) {
            $i = 0;
            foreach ($refunds as $item) {
                $data[$i][] = $item->created_at;
                $data[$i][] = $item->refund_no;
                $data[$i][] = $item->TypeText;
                $data[$i][] = $item->reason;
                $data[$i][] = $item->amount;
                $data[$i][] = $item->StatusText;
                if ($item->status == 3) {
                    $data[$i][] = $item->updated_at;
                } else {
                    $data[$i][] = '';
                }
                $data[$i][] = $item->orderItem->item_name;
                $data[$i][] = $item->order->order_no;
                $data[$i][] = $item->content;
                $i++;
            }
        }
        return $data;
    }

    /**
     * 后台处理退换货操作
     *
     * @param $id      申请ID
     * @param $opinion 审核意见 1：审核通过 2：拒绝
     * @param $adminID 管理员ID
     * @param $remarks 处理说明
     */
    public function handleRefund($id, $opinion, $adminID, $remarks)
    {
        $refund = $this->refundRepository->find($id);
        $order = $this->orderRepository->find($refund->order_id);

        $action = $opinion == 1 ? 'agree' : 'refuse';
        $note = $opinion == 1 ? '同意用户申请' : '拒绝用户申请';

        //更新申请状态
        $refund->status = $opinion;
        $refund->save();

        //写入日志
        $this->refundService->refundLog($id, 0, $adminID, $action, $note, $remarks);

        //如果订单未发货，只有退款申请
        /*if ($order->distribution_status == 0) {*/

        if ($refund->type == 1) {   //如果是退款申请
            if ($opinion == 1) //同意申请
            {
                /*$this->refundService->refundLog($id, 0, $adminID, 'receipt', '本次申请已完成', $remarks);  //本次申请完成,写入日志*/

                //TODO:: Finance refund amount

                $refund->status = Refund::STATUS_SHOP_PAID;
                $refund->save();

                /*如果售后申请的商品数量=订单item的商品数量，修改item的status*/
                $orderItem = OrderItem::find($refund->order_item_id);
                if ($refund->quantity == $orderItem->quantity) {
                    $orderItem->status = 2;
                    $orderItem->save();
                }

                $refundModel = Refund::find($refund->id);

                /*if ($this->refundService->checkOrderRefund($order, $refund) OR env('JW_REFUND')) {*/
                if ($this->refundService->checkOrderRefund($order, $refund)) {
                    $order->status = 6;
                    $order->save();

                    /*取消积分以及分销佣金*/
                    event('order.canceled', $order->id);
                    event('agent.order.canceled', $order->id);
                } else {
                    event('order.refund.complete', $refundModel);
                    event('agent.order.refund', $refundModel);
                }

                $this->changeOrderStatus($order->id);

                event('refund.agree', [$refund, $order->id]);
            }
        } else {  //订单已发货
            if ($opinion == 1)   // 同意申请
            {
                $refund->status = 5;
            } else {
                //更新申请状态
                $refund->status = $opinion;
            }
            $refund->save();
            event('refund.agree', [$refund, $order->id]);
        }

        event('refund.service.changed', [$refund, $note]);
    }

    /**
     * 商城确认收到退货操作
     *
     * @param $type
     * @param $id
     * @param $uid
     */
    public function confirmReceipt($type, $id, $uid, $remarks)
    {

        $refund = $this->refundRepository->find($id);
        $order = $this->orderRepository->find($refund->order_id);

        //如果是退货申请：1、申请完成  2、财务处理 3、订单状态
        $message = '';
        if ($type == 1 OR $type == 4) {
            //1.申请完成

            /*$refund->status = Refund::STATUS_COMPLETE;*/
            $refund->status = Refund::STATUS_SHOP_PAID;
            $refund->save();

            /*如果售后申请的商品数量=订单item的商品数量，修改item的status*/
            $orderItem = OrderItem::find($refund->order_item_id);
            if ($refund->quantity == $orderItem->quantity) {
                $orderItem->status = 2;
                $orderItem->save();
            }

            $this->changeOrderStatus($order->id);

            /*$this->refundService->refundLog($refund->id, 0, $uid, 'receipt', '商城已收到退货，本次申请已完成', ''); */
            $message = '商城已收到退货，等待退款';
            $this->refundService->refundLog($refund->id, 0, $uid, 'accept', $message, $remarks);

            $refundModel = Refund::find($refund->id);
            /*event('order.refund.complete', $refundModel);
            event('agent.order.refund', $refundModel);*/

            //TODO:: Finance refund amount

            //3.订单状态
            if ($this->refundService->checkOrderRefund($order, $refund)) {
                $order->status = 6;
                $order->save();

                /*取消积分以及分销佣金*/
                event('order.canceled', $order->id);
                event('agent.order.canceled', $order->id);
            } else {
                event('order.refund.complete', $refundModel);
                event('agent.order.refund', $refundModel);
            }
        }
        /*else {     //换货申请

            $refund->status = 7;    //商城已收货
            $refund->save();

            $message = '商城已收到退货，等待商城再次发货';
            $this->refundService->refundLog($refund->id, 0, $uid, 'accept', '', $message);
        }*/

        event('refund.service.changed', [$refund, $message]);
    }

    /**
     * 拒绝退款
     * @param $id
     * @param $uid
     * @param $remarks
     * @param $log_action ::为了判断首次退货退款被拒绝退款，不给二次售后的机会
     */
    public function reject($id, $uid, $remarks, $log_action)
    {
        $refund = $this->refundRepository->find($id);
        $message = '拒绝退款';

        if ($log_action == 'reject') {
            $this->refundService->refundLog($refund->id, 0, $uid, $log_action, $message, $remarks);
        }

        $refund->status = Refund::STATUS_CANCEL;
        $refund->save();

        $this->refundService->refundLog($refund->id, 0, $uid, 'close', $message, $remarks);
        event('refund.service.changed', [$refund, $message]);
    }
}
