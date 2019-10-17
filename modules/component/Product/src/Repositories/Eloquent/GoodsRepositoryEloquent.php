<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Product\Repositories\Eloquent;

use GuoJiangClub\Component\Product\Models\Goods;
use GuoJiangClub\Component\Product\Repositories\GoodsRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-27
 * Time: 17:31.
 */
class GoodsRepositoryEloquent extends BaseRepository implements GoodsRepository
{
    //use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Goods::class;
    }

    /**
     * get one goods on sale.
     *
     * @param $id
     *
     * @return mixed
     */
    public function findOneById($id)
    {
        return $this->findWhere(['id' => $id, 'is_del' => 0])->first();
    }
}
