<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 15:41
 */

namespace iBrand\EC\Open\Core\Models;


use iBrand\EC\Open\Core\Discount\Contracts\DiscountItemContract;

class Product extends \iBrand\Component\Product\Models\Product implements DiscountItemContract
{
    /**
     * get item categories.
     *
     * @return mixed
     */
    public function getCategories()
    {
        return $this->goods->getCategories();
    }
}