<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use GuoJiangClub\EC\Open\Server\Transformers\OrderTransformer;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrders()
    {
        $orderConditions['channel'] = \request('channel') ? \request('channel') : 'ec';

        if (request('order_no')) {
            $orderConditions['order_no'] = request('order_no');
        }

        if (request('status')) {
            $orderConditions['status'] = request('status');
        } else {
            $orderConditions['status'] = ['status', '<>', 0];
            $orderConditions['status2'] = ['status', '<>', 9];
        }

        $orderConditions['user_id'] = request()->user()->id;

        $itemConditions = [];

        $limit = request('limit') ? request('limit') : 10;

        if ($criteria = request('criteria')) {
            $itemConditions['order_no'] = ['order_no', 'like', '%'.$criteria.'%'];
            $itemConditions['item_name'] = ['item_name', 'like', '%'.$criteria.'%'];
            $itemConditions['item_id'] = ['item_id', 'like', '%'.$criteria.'%'];

            $order = $this->orderRepository->getOrdersByCriteria($orderConditions, $itemConditions, $limit);
        } else {
            $order = $this->orderRepository->getOrdersByConditions($orderConditions, $itemConditions,
                $limit, ['items', 'shippings', 'adjustments', 'items.product', 'items.product.goods']);
        }

        $transformer = request('transformer') ? request('transformer') : 'list';

        return $this->response()->paginator($order, new OrderTransformer($transformer));
    }

    public function getOrderDetails($orderno)
    {
        $user = request()->user();

        $order = $this->orderRepository->getOrderByNo($orderno);

        if ($user->cant('update', $order)) {
            return $this->failed('无权操作');
        }

        return $this->response()->item($order, new OrderTransformer());
    }
}
