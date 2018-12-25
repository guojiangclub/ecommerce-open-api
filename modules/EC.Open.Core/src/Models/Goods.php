<?php

namespace iBrand\EC\Open\Core\Models;

use iBrand\EC\Open\Core\Discount\Contracts\DiscountItemContract;

class Goods extends \iBrand\Component\Product\Models\Goods implements DiscountItemContract
{

    /**
     * get item relation key codes.
     *
     * @return mixed
     */
    public function getChildKeyCodes()
    {
        $codes = [];
        $products = $this->products;
        foreach ($products as $product) {
            $codes[] = $product->getKeyCode();
        }
        return $codes;
    }

    /**
     * get item categories.
     *
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }
}