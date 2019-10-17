<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Repositories\Eloquent;

use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * 根据订单编号获得订单数据.
     *
     * @return mixed
     */
    public function getOrderByNo($no)
    {
        return $this->with(['items', 'shippings'])->findByField('order_no', $no)->first();
    }

    /**
     * 根据状态查询订单数据.
     *
     * @param $status
     */
    public function getOrderByStatus($input, $user_id)
    {
        $order = $this->orderBy('created_at', 'desc')
            ->with('items')->scopeQuery(function ($query) use ($input, $user_id) {
                $query = $query->where(['user_id' => $user_id]);
                if (isset($input['status']) && 0 != $input['status']) {
                    $query->where(['status' => $input['status']]);
                } else {
                    $query->where('status', '>', 0);
                }
                if (isset($input['type'])) {
                    $query->where(['type' => $input['type']]);
                }

                return $query;
            })->paginate(15);

        return $order;
    }

    public function getOrdersByConditions($orderConditions, $itemConditions, $limit = 15, $withs = ['items'])
    {
        $this->applyConditions($orderConditions);

        foreach ($withs as $with) {
            $this->with($with);
        }

        return $this->orderBy('created_at', 'desc')->scopeQuery(function ($query) use ($itemConditions) {
            if (count($itemConditions) > 0) {
                $query = $query->whereHas('items', function ($query) use ($itemConditions) {
                    foreach ($itemConditions as $field => $value) {
                        if (is_array($value)) {
                            list($field, $condition, $val) = $value;
                            $query = $query->where($field, $condition, $val);
                        } else {
                            $query = $query->where($field, '=', $value);
                        }
                    }

                    return $query;
                });
            }

            return $query;
        })->paginate($limit);
    }

    public function getOrdersByCriteria($andConditions, $orConditions, $limit = 15)
    {
        $orderItemTable = config('ibrand.app.database.prefix', 'ibrand_').'order_item';
        $query = $this->model->join($orderItemTable, $this->model->getQualifiedKeyName(), '=', $orderItemTable.'.order_id');

        foreach ($andConditions as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $query = $query->where($this->model->getTable().'.'.$field, $condition, $val);
            } else {
                $query = $query->where($this->model->getTable().'.'.$field, '=', $value);
            }
        }

        $ids = $query->where(function ($query) use ($orConditions) {
            $index = 1;
            foreach ($orConditions as $field => $value) {
                if (is_array($value)) {
                    list($field, $condition, $val) = $value;
                    if (1 == $index) {
                        $query = $query->where($field, $condition, $val);
                    } else {
                        $query = $query->orWhere($field, $condition, $val);
                    }
                } else {
                    if (1 == $index) {
                        $query = $query->where($field, '=', $value);
                    } else {
                        $query = $query->orWhere($field, '=', $value);
                    }
                }
                ++$index;
            }
        })->select($this->model->getTable().'.*')->get()->pluck('id');

        return $this->orderBy('created_at', 'desc')->with('items')->scopeQuery(function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        })->paginate($limit);
    }

    /**
     * 根据状态和用户获取订单的数量.
     *
     * @param $user_id
     * @param $status
     *
     * @return mixed
     */
    public function getOrderCountByUserAndStatus($user_id, $status)
    {
        return $this->model->where('user_id', $user_id)->where('status', $status)->count();
    }
}
