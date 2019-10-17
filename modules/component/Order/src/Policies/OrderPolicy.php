<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Policies;

use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Models\OrderItem;
use GuoJiangClub\Component\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Order $order)
    {
        return $user->id == $order->user_id;
    }

    public function submit(User $user, Order $order)
    {
        //只有该订单属于该用户，并且订单处于临时状态下才能提交订单。
        return $user->id === $order->user_id and Order::STATUS_TEMP === $order->status;
    }

    public function pay(User $user, Order $order)
    {
        //只有该订单属于该用户，并且订单处于待付款状态下，才能进行支付操作。
        return $user->id == $order->user_id and Order::STATUS_NEW == $order->status;
    }

    public function cancel(User $user, Order $order)
    {
        //只有该订单属于该用户，并且订单处于待付款状态下，才能进行取消操作。
        return $user->id == $order->user_id and Order::STATUS_NEW == $order->status;
    }

    public function received(User $user, Order $order)
    {
        return $user->id == $order->user_id and Order::STATUS_DELIVERED == $order->status;
    }

    public function delete(User $user, Order $order)
    {
        //只有已取消订单用户才能够删除订单
        return $user->id == $order->user_id and Order::STATUS_CANCEL == $order->status;
    }

    public function review(User $user, Order $order, OrderItem $orderItem)
    {
        //只有已收货的订单才能够进行评价商品和订单
        return $user->id == $order->user_id and $order->id == $orderItem->order_id and Order::STATUS_RECEIVED == $order->status;
    }
}
