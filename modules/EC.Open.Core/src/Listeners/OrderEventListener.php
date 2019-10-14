<?php

namespace GuoJiangClub\EC\Open\Core\Listeners;

use Carbon\Carbon;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Models\OrderItem;
use GuoJiangClub\Component\Point\Repository\PointRepository;
use GuoJiangClub\EC\Open\Core\Jobs\AutoCancelOrder;

class OrderEventListener
{

    protected $listens = [
        'order.paid' => 'onOrderPaid',
        'order.submitted' => 'onOrderSubmitted'
    ];

    protected $point;

    public function __construct(PointRepository $pointRepository)
    {
        $this->point = $pointRepository;
    }

    public function onOrderSubmitted(Order $order){

        if ($order->status == Order::STATUS_NEW) {

            $delayTime = config('ibrand.app.order_auto_cancel');

            $job = (new AutoCancelOrder($order))
                ->delay(Carbon::now()->addMinute($delayTime));

            dispatch($job);
        }
    }

    public function onOrderPaid(Order $order)
    {
        if (!config('ibrand.app.point.enable')) {  //如果未启用积分模块，则不进行积分操作
            return;
        }

        foreach ($order->getItems() as $orderItem) {
            $point = $this->point->getPointByItem(OrderItem::class, $orderItem->id);

            if ($point) {
                continue;
            }//该订单已经存在积分值

            $this->point->create(['user_id' => $order->user_id
                , 'action' => 'order_item'
                , 'note' => '购物送积分'
                , 'value' => $orderItem->total / 100
                , 'status' => 0
                , 'item_type' => OrderItem::class
                , 'item_id' => $orderItem->id
            ]);
        }
    }

    public function subscribe($events)
    {
        foreach ($this->listens as $event => $listener) {
            $events->listen($event, __CLASS__ . '@' . $listener);
        }
    }

}