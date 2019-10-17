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


use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use GuoJiangClub\EC\Open\Core\Models\Goods;
use GuoJiangClub\EC\Open\Core\Services\DiscountService;

class DiscountController extends Controller
{
    private $discount;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discount = $discountRepository;
    }

    public function create()
    {
        $discount = $this->discount->create(request()->except('rule_type', 'rule_value', 'action_type', 'action_value'));

        $ruleType = request('rule_type');

        if ($ruleType == 'cart_quantity') {
            $ruleData = ['count' => request('rule_value')];
        }else{
            $ruleData = ['amount' => request('rule_value')];
        }

        $actionType = request('action_type');

        if ($actionType == 'order_fixed_discount') {
            $actionData = ['amount' => request('action_value')];
        }else{
            $actionData = ['percentage' => request('action_value')];
        }

        $discount->rules()->create(['type' => $ruleType, 'configuration' => json_encode($ruleData)]);
        $discount->actions()->create(['type' => $actionType, 'configuration' => json_encode($actionData)]);

        return $this->success();
    }

    public function shoppingCartDiscount()
    {
        $ids = request('ids');

        if (empty($ids)) {
            return $this->failed('必填参数缺失');
        }

        $discount = [];
        $coupon   = [];
        foreach ($ids as $id) {
            $goods = Goods::find($id);
            if (!$goods) {
                continue;
            }

            $discounts = app(DiscountService::class)->getDiscountsByGoods($goods);

            if (!$discounts || count($discounts) == 0) {
                continue;
            }

            $coupon_based_0 = collect_to_array($discounts->where('coupon_based', 0));
            foreach ($coupon_based_0 as $d) {
                if (!array_key_exists($d['id'], $discount)) {
                    $discount[$d['id']] = $d;
                }
            }

            $coupon_based_1 = collect_to_array($discounts->where('coupon_based', 1));
            foreach ($coupon_based_1 as $c) {
                if (!array_key_exists($c['id'], $coupon)) {
                    $coupon[$c['id']] = $c;
                }
            }
        }

        $result = ['coupons' => array_values($coupon), 'discounts' => array_values($discount)];

        return $this->success($result);
    }
}