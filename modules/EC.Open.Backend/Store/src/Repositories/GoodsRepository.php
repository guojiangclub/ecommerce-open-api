<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\Goods;

/**
 * Class GoodsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GoodsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Goods::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * 根据产品ID获取产品对应的属性值
     * @param $id
     * @return array
     */
    public function getAttrArray($id)
    {
        $attrInfo = [];
        $goods = $this->find($id);
        foreach ($goods->hasManyAttribute as $key => $attribute) {
            $attrInfo[$key]['attribute_id'] = $attribute->pivot->attribute_id;
            $attrInfo[$key]['attribute_value'] = $attribute->pivot->attribute_value;
            $attrInfo[$key]['attribute_value_id'] = $attribute->pivot->attribute_value_id;

        }
        return $attrInfo;
    }


    /**
     * 获取所有下架商品
     * @param $limit
     * @return mixed
     */
//    public function getOfflineGoodsPaginated($limit)
//    {
//        return $this->scopeQuery(function ($query) {
//            return $query->where('is_del', 2)->orderBy('updated_at', 'desc');
//        })->paginate($limit);
//    }

    /**
     * 获取所有下架商品总数
     * @return mixed
     */
    public function getOfflineGoodsCount()
    {
        return $this->model->where('is_del', 2)->count();
    }

    /**
     * 获取所有商品总数
     * @return mixed
     */
    public function getAllGoodsCount()
    {
        return $this->model->count();
    }


    public function getGoodsPaginated($where, $where_ = [], $ids = [], $limit = 50, $order_by = 'created_at', $sort = 'desc')
    {
        if ($ids === false) {
            return new Collection();
        }

        $data = $this->scopeQuery(function ($query) use ($where, $where_, $ids, $order_by, $sort) {
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if (is_array($where_)) {
                foreach ($where_ as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if (count($ids)) {
                $query = $query->whereIn('id', $ids);
            }
            return $query->with('model')->with('categories')->orderBy($order_by, $sort);
        });

        if ($limit == 0) {
            return $data->all();
        } else {
            return $data->paginate($limit);
        }
    }


    /**
     * 获取商品所属分类ID
     * @param $data
     * @return array
     */
    public function getGoodCateIDs($data)
    {
        $cateIds = [];
        foreach ($data as $val) {
            array_push($cateIds, $val->pivot->category_id);
        }
        return $cateIds;
    }


    /**商品统一操作
     * @param string $value
     * @param string $operation
     * @param array $goods_id
     * @return array
     */
    public function operationLineGoods($operation = '', $value = '', $goods_id = [])
    {
        $data = [];
        if (!empty($goods_id) && !empty($operation)) {
            $i = 0;
            foreach ($goods_id as $item) {
                if ($this->update(["$operation" => $value], $item)) {
                    $data[$i] = $this->update(["$operation" => $value], $item);
                }
                $i++;
            }
        }
        return $data;
    }


    /**通过商品ID获取商品分页信息
     * @param array $goods_id
     * @param int $is_del
     * @param int $limit
     * @return bool
     */
    public function getGoodsById($goods_id = [], $is_del = 0, $limit = 50)
    {
        if (count($goods_id) > 0) {
            $goods = Goods::where('is_del', $is_del)->whereIn('id', $goods_id)->orderBy('updated_at', 'desc');
            if ($limit == 0) {
                return $goods->get();
            } else {
                return $goods->paginate($limit);
            }
        } else {
            return [];
        }
    }

    /**
     * @return mixed
     * 获取推荐商品
     */
    public function getCommendGoods()
    {
        return $this->scopeQuery(function ($query) {
            $query = $query->where(['is_commend' => 1]);
            $query = $query->limit(4);

            return $query->orderBy('updated_at', 'desc');
        })->all();
    }

    public function getGoodsByName($name)
    {
        return $this->scopeQuery(function ($query) use ($name) {
            $query = $query->where('is_del', 0)->where('name', 'like', '%' . $name . '%');
            return $query->orderBy('updated_at', 'desc');
        })->get();
    }

}
