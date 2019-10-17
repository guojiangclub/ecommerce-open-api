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

use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use GuoJiangClub\EC\Open\Core\Services\DiscountService;
use GuoJiangClub\EC\Open\Server\Transformers\CouponTransformer;

class CouponController extends Controller
{
    private $couponRepository;
    private $discountService;
    private $discountRepository;

    public function __construct(
        CouponRepository $couponRepository, DiscountService $discountService, DiscountRepository $discountRepository
    )
    {
        $this->couponRepository = $couponRepository;
        $this->discountService = $discountService;
        $this->discountRepository = $discountRepository;
    }

    public function index()
    {
        $type = request('type') ?: 'valid';

        $user = request()->user();

        if ('valid' == $type) {
            $coupons = $this->couponRepository->findActiveByUser($user->id);
        } elseif ('used' == $type) {
            $coupons = $this->couponRepository->findUsedByUser($user->id);
        } else {
            $coupons = $this->couponRepository->findInvalidByUser($user->id);
        }

        return $this->response()->paginator($coupons, new CouponTransformer());
    }

    public function show($couponId)
    {
        $coupon = $this->couponRepository->with('discount', 'discount.rules', 'discount.actions')->find($couponId);

        $user = request()->user();

        if ($user->cant('update', $coupon)) {
            return $this->failed('无权操作');
        }

        return $this->success($coupon);
    }

    public function create()
    {
        $discount = $this->discountRepository->create(request()->except('rule_type', 'rule_value', 'action_type', 'action_value'));

        $ruleType = request('rule_type');

        if ($ruleType == 'cart_quantity') {
            $ruleData = ['count' => request('rule_value')];
        } else {
            $ruleData = ['amount' => request('rule_value')];
        }

        $actionType = request('action_type');

        if ($actionType == 'order_fixed_discount') {
            $actionData = ['amount' => request('action_value')];
        } else {
            $actionData = ['percentage' => request('action_value')];
        }

        $discount->rules()->create(['type' => $ruleType, 'configuration' => json_encode($ruleData)]);
        $discount->actions()->create(['type' => $actionType, 'configuration' => json_encode($actionData)]);

        return $this->success();
    }

    public function take()
    {
        $discount = $this->discountRepository->find(request('discount_id'));

        if(!$discount->coupon_based){
            return $this->failed('非优惠券，无法领取');
        }

        $coupon = $this->couponRepository->create(['discount_id'=>request('discount_id'),'user_id'=>request()->user()->id,
            'code'=>uniqid()]);

        return $this->success($coupon);
    }
}
