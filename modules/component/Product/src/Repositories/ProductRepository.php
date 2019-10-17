<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Product\Repositories;

/*
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-27
 * Time: 17:30
 */

use Prettus\Repository\Contracts\RepositoryInterface;

interface ProductRepository extends RepositoryInterface
{
    /**
     * get one product by sku.
     *
     * @param $sku
     *
     * @return mixed
     */
    public function findOneBySku($sku);
}
