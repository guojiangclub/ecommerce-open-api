<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\GoodsCategory;


/**
 * Class GoodsCategoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GoodsCategoryRepository extends BaseRepository 
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GoodsCategory::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    

    /**
     * 根据分类ID获取商品ID
     * @param $categoryID
     * @return mixed
     */
    public function getGoodsIDbyCategoryID($categoryID)
    {
        $data = $this->findWhere(['category_id' => $categoryID],['goods_id']);
        $Ids = [];
        foreach ($data as $item)
        {
            $Ids[] = $item->goods_id;
        }
        return $Ids;
    }
}
