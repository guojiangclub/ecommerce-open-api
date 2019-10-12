<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\OrderItem;
use GuoJiangClub\EC\Open\Backend\Store\Exceptions\GeneralException;

/**
 * Class OrderGoodsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OrderItemRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OrderItem::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $order_id
     * @return mixed
     * @throws GeneralException
     */

    public function findOrThrowException($order_id)
    {

        $orderGoods=OrderItem::withTrashed()->where(['order_id'=>$order_id])->first();

        if(is_null($orderGoods)){

            throw new GeneralException('订单所购商品不存在');

        }

        return $orderGoods;

    }


    /**
     * 根据订单ID和产品ID获取订单产品数据
     * @param $order_id
     * @param $order_product_id
     * @return mixed
     */
    public function getOrderGoodsByIds($order_id, $order_product_id)
    {
        return $this->findWhere(['order_id' => $order_id, 'id' => $order_product_id])->first();
    }



        /**通过订单ID获取产品信息
         * @param $order_id
         * @return mixed
         */

        public  function  orderGoodsByOrderId($order_id){

            return $this->with('products')->findWhere(['order_id' => $order_id]);

        }
}
