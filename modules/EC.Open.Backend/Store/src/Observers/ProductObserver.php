<?php
namespace iBrand\EC\Open\Backend\Store\Observers;

use iBrand\EC\Open\Backend\Store\Model\Product;

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

        if (settings('goods_price_protection_enabled') AND $goods = $product->goods) { //如果启用了价格保护，则自动下架

            //1. 价格保护启用后，默认低于吊牌价三折就自动下架
            $percentage = (settings('goods_price_protection_discount_percentage') ? settings('goods_price_protection_discount_percentage') : 30) / 100;

            if ($product->sell_price < $goods->market_price * $percentage) {

                $goods->is_del = 2;
                $goods->save();

            }
        }
    }
}