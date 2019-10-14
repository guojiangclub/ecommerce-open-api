<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\OrderAdjustment;

/**
 * Class DiscountConditionRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OrderAdjustmentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OrderAdjustment::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getOrderAdjustmentHistory($where, $limit = 50, $time = [])
    {
        if(isset($where['order_no'])){
            $order_no=$where['order_no'];
            unset($where['order_no']);
        }

        $query = $this->model->whereIn('origin_type', ['discount', 'discount_by_market_price']);      

        if (count($where) > 0) {
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
            foreach ($time as $key => $value) {             
                if (is_array($value)) {
                    list($operate, $va) = $value;
                    $query = $query->where($key, $operate, $va);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }
        
        if(isset($order_no[1])){
            $query = $query
                ->whereHas('order', function ($query) use ($order_no) {
                    return $query->where('order_no','like',$order_no[1]);
                })
            ->with('order.user')->orderBy('created_at', 'desc');
        }else{
            $query = $query->with('order')->with('order.user')->orderBy('created_at', 'desc');
        }


        if ($limit == 0) {
            return $query->all();
        } else {
            return $query->paginate($limit);
        }

    }
}
