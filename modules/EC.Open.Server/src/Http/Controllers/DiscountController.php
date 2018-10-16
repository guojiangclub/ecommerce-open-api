<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Server\Http\Controllers;


use iBrand\Component\Discount\Repositories\DiscountRepository;

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
}