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

use GuoJiangClub\Component\Product\Models\Product;
use GuoJiangClub\Component\Product\Repositories\ProductRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * get one product by sku.
     *
     * @param $sku
     *
     * @return mixed
     */
    public function findOneBySku($sku)
    {
        return $this->findByField('sku', $sku)->first();
    }
}
