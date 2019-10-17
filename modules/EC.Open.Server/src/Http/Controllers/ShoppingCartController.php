<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use Cart;
use GuoJiangClub\Component\Product\Models\Goods;
use GuoJiangClub\Component\Product\Models\Product;

class ShoppingCartController extends Controller
{
    protected $discountService;
    protected $goodsUserLimit;
    protected $goodsLimit;

    public function __construct()
    {
    }

    public function index()
    {
        if (empty(request()->all())) {
            $carts = Cart::search(['channel' => 'normal']);
        } else {
            $carts = Cart::search(request()->all());
        }

        //返回购物车中目前所有商品的库存
        foreach ($carts as $item) {
            if ($item and $item->model) {
                $item['stock_qty'] = $item->model->stock_qty;
            } else {
                $item['stock_qty'] = 0;
            }
        }

        return $this->success($carts);
    }

    public function store()
    {
        $carts = request()->all();

        if (0 == count($carts)) {
            return $this->success();
        }

        foreach ($carts as $cart) {
            //设置属性值
            $attributes = isset($cart['attributes']) ? $cart['attributes'] : [];

            if (isset($cart['attributes']) and !isset($cart['attributes']['sku'])) {
                Cart::associate(Goods::class);
                $attributes['type'] = 'spu';
            } else {
                Cart::associate(Product::class);
                $attributes['type'] = 'sku';
            }

            if (!isset($cart['id'])) {
                continue;
            }

            $item = Cart::add($cart['id'], $cart['name'], $cart['qty'], $cart['price'], $attributes);

            if (!$item || !$item->model) {
                return $this->failed('商品数据错误');
            }

            if (2 == $item->model->is_del) {
                //已下架，需要删除购物车数据
                Cart::remove($item->rawId());

                return $this->failed('商品已下架');
            }

            if (($qty = $this->getIsInSaleQty($item, $item->qty)) > 0) {
                Cart::update($item->rawId(), ['qty' => $qty]);
            } else {
                Cart::remove($item->rawId());

                return $this->failed( '商品库存不足,请重新选择');
            }

            Cart::update($item->rawId(), ['status' => 'online', 'market_price' => $item->model->market_price, 'channel' => 'normal']);
        }

        return $this->success(Cart::all());
    }

    public function update($id)
    {
        $item = Cart::get($id);

        if (!$item) {
            return $this->failed('购物车数据不存在');
        }

        $attributes = request('attributes');

        if ($attributes['qty'] <= $item->model->stock_qty) {
            $item = Cart::update($id, $attributes);

            return $this->success($item);
        }

        return $this->failed('库存不够');
    }

    public function delete($id)
    {
        return $this->success(Cart::remove($id));
    }

    public function clear()
    {
        Cart::destroy();

        return $this->success(Cart::all());
    }

    public function count()
    {
        return $this->success(Cart::count());
    }

    public function getIsInSaleQty($item, $qty)
    {
        if ($qty <= 0) {
            return 0;
        }
        if ($item->model->getIsInSale($qty)) {
            return $qty;
        }

        return $this->getIsInSaleQty($item, $qty - 1);
    }
}
