<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/1
 * Time: 15:42
 */

namespace iBrand\EC\Open\Backend\Store\Listeners;


use iBrand\EC\Open\Backend\Store\Jobs\ProcessMultiGroupon;
use iBrand\EC\Open\Backend\Store\Model\MultiGrouponItems;
use iBrand\EC\Open\Backend\Store\Repositories\MultiGrouponItemRepository;
use iBrand\EC\Open\Backend\Store\Service\PaymentService;

class MultiGrouponRefundEventListener
{
    protected $multiGrouponItemRepository;
    protected $paymentService;

    public function __construct(MultiGrouponItemRepository $multiGrouponItemRepository,
                                PaymentService $paymentService)
    {
        $this->multiGrouponItemRepository = $multiGrouponItemRepository;
        $this->paymentService = $paymentService;
    }

    public function onGrouponFailRefund($grouponItems)
    {
        foreach ($grouponItems as $item) {
            \Log::info('进入第一步');
            $this->paymentService->multiGrouponRefund($item->id);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'multiGroupon.order.fail',
            'iBrand\EC\Open\Backend\Store\Listeners\MultiGrouponRefundEventListener@onGrouponFailRefund'
        );
    }
}