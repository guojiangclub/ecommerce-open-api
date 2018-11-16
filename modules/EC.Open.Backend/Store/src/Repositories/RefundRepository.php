<?php

namespace iBrand\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use iBrand\EC\Open\Backend\Store\Model\Refund;

/**
 * Class RefundRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RefundRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Refund::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getRefundsPaginated($view, $where, $value, $time = [], $complete_time = [], $limit = 15)
    {
        return $this->scopeQuery(function ($query) use ($where, $view, $time, $complete_time) {
            if (is_array($where) AND count($where) > 0) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if (count($time) > 0) {
                $query = $query->whereBetween('created_at', $time);
            }

            if (count($complete_time) > 0) {
                $query = $query->whereBetween('updated_at', $complete_time);
            }

            if ($view !== 'all') {
                switch ($view) {
                    case 1: //处理中
                        $query = $query->whereNotIN('status', [0, 3, 4]);
                        break;

                    case 2: //已完成
                        $query = $query->where('status', 3);
                        break;

                    case 8: //待退款
                        $query = $query->where('status', 8);
                        break;

                    default:    //待审核
                        $query = $query->where('status', 0);
                }
            }

            return $query->orderBy('updated_at', 'desc');
        })->whereHas('order', function ($query) use ($value) {
            if ($value) {
                $query->where('order_no', 'like', '%' . $value . '%');
            }
        })->paginate($limit);
    }

    public function getTimeBetweenRefund()
    {
        return $this->scopeQuery(function ($query) {
            return $query->where('status', '5')->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-15 day')));
        })->all();
    }
}
