<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Product\Repositories\Eloquent;

use iBrand\Component\Product\Models\Product;
use iBrand\Component\Product\Repositories\ProductRepository;
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
