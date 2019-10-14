<?php

namespace GuoJiangClub\EC\Open\Core\Applicators;

use GuoJiangClub\Component\Order\Models\Adjustment;
use GuoJiangClub\Component\Discount\Distributors\PercentageIntegerDistributor;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Point\Repository\PointRepository;

class PointApplicator
{
    private $distributor;
    private $point;

    public function __construct(PercentageIntegerDistributor $distributor
        , PointRepository $pointRepository)
    {
        $this->distributor = $distributor;
        $this->point = $pointRepository;
    }

    public function apply(Order $order, $point)
    {
        $uid = $order->user_id;

        $adjustment = new Adjustment([
            'type' => 'order_point_discount',
            'label' => '使用积分',
            'origin_type' => 'point',
            'origin_id' => $uid
        ]);

        $amount = (-1) * $point * config('ibrand.app.point.order_proportion');

        if ($amount == 0) {
            return;
        }

        $adjustment->amount = $amount;

        $order->addAdjustment($adjustment);

        $splitDiscountAmount = $this->distributor->distribute($order->getItems()->pluck('total')->toArray(), $amount);

        $i = 0;

        foreach ($order->getItems() as $item) {
            $splitAmount = $splitDiscountAmount[$i++];
            $item->divide_order_discount += $splitAmount;
            $item->recalculateAdjustmentsTotal();
        }

        $this->point->create([
            'user_id' => $order->user_id,
            'action' => 'order_discount',
            'note' => '订单使用积分折扣',
            'value' => (-1) * $point,
            'valid_time' => 0,
            'item_type' => Order::class,
            'item_id' => $order->id
        ]);
    }

}