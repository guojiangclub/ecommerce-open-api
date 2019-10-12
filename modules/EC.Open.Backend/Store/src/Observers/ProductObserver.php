<?php
namespace GuoJiangClub\EC\Open\Backend\Store\Observers;

use GuoJiangClub\EC\Open\Backend\Store\Model\Product;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/3
 * Time: 15:20
 */
class ProductObserver
{
    public function saved(Product $product)
    {
        if ($goods = $product->goods) {

            if ($minPrice = $goods->hasManyProducts()->min('sell_price') AND $minPrice > 0) {
                $goods->min_price = $minPrice;
            }
            if ($maxPrice = $goods->hasManyProducts()->max('sell_price') AND $maxPrice > 0) {
                $goods->max_price = $maxPrice;
            }

            if ($minMarketPrice = $goods->hasManyProducts()->min('market_price') AND $minMarketPrice > 0) {
                $goods->min_market_price = $minMarketPrice;
            }
            if ($maxMarketPrice = $goods->hasManyProducts()->max('market_price') AND $maxMarketPrice > 0) {
                $goods->market_price = $maxMarketPrice;
            }

            if ($minPrice > 0 OR $maxPrice > 0) {
                $goods->save();
            }
        }
    }
}