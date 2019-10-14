<?php


namespace GuoJiangClub\EC\Open\Core\Models;


use GuoJiangClub\EC\Open\Core\Discount\Contracts\DiscountItemContract;

class Product extends \GuoJiangClub\Component\Product\Models\Product implements DiscountItemContract
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