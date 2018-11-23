<?php

namespace iBrand\EC\Open\Core\Listeners;

use iBrand\Component\Order\Models\Order;
use iBrand\Component\Order\Models\OrderItem;
use iBrand\Component\Point\Repository\PointRepository;

class OrderEventListener
{

    protected $listens = [
        'order.paid' => 'onOrderPaid',
    ];

    protected $point;

    public function __construct(PointRepository $pointRepository)
    {
        $this->point = $pointRepository;
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