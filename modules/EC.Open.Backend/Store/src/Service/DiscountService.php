<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Service;
use GuoJiangClub\Component\User\Models\User;
use GuoJiangClub\EC\Open\Backend\Store\Model\ElDiscount;
use GuoJiangClub\EC\Open\Backend\Store\Model\ElDiscountAction;
use GuoJiangClub\EC\Open\Backend\Store\Model\ElDiscountRule;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ProductRepository;
use Excel;


class DiscountService
{
    protected $productRepository;
    protected $cache;

    public function __construct()
    {
        $this->productRepository = app(ProductRepository::class);
        $this->cache = cache();
    }

    /**获取搜索到的全部导Excel数据
     *
     * @param array $coupons
     *
     * @return string
     */

    public function searchAllCouponsHistoryExcel($coupons = [])
    {
        $date = [];
        if (count($coupons) > 0) {
            $i = 0;
            foreach ($coupons as $coupon) {
                $date[$i][] = $coupon->used_at;
                $date[$i][] = ElDiscount::find($coupon->discount_id)->title;
                $date[$i][] = $coupon->code;
                $date[$i][] = $coupon->order[0]->order_no;
                $date[$i][] = $coupon->order[0]->total;

                if ($coupon->order[0]->status == 7) {
                    $date[$i][] = "退款中";
                } elseif ($coupon->order[0]->status == 1) {
                    $date[$i][] = "待付款";
                } elseif ($coupon->order[0]->status == 1) {
                    $date[$i][] = "待付款";
                } elseif ($coupon->order[0]->status == 2) {
                    $date[$i][] = "待发货";
                } elseif ($coupon->order[0]->status == 3) {
                    $date[$i][] = "配送中待收货";
                } elseif ($coupon->order[0]->status == 4) {
                    $date[$i][] = "已收货待评价";
                } elseif ($coupon->order[0]->status == 5) {
                    $date[$i][] = "已完成";
                } elseif ($coupon->order[0]->status == 6) {
                    $date[$i][] = "已取消";
                }
                $date[$i][] = User::find($coupon->user_id)->name;

                $date[$i][] = $coupon->note;
                $i++;
            }
        }

        return $date;
    }

    /**
     * 优惠券领取记录导出数据格式化
     *
     * @param array $coupons
     *
     * @return array
     */
    public function couponsGetDataExcel($coupons = [])
    {
        $date = [];
        if (count($coupons) > 0) {
            $i = 0;
            foreach ($coupons as $coupon) {
                foreach ($coupon->created_at as $value) {
                    $date[$i][] = basename($value, "." . substr(strchr($value, '.'), 1));
                }
                unset($date[$i][2]);
                unset($date[$i][1]);

                $date[$i][] = $coupon->code;

                $user = User::find($coupon->user_id);
                $date[$i][] = $user ? $user->mobile : '';
                $date[$i][] = $coupon->used_at ? '已使用' : '未使用';
                $date[$i][] = $coupon->used_at;
                $i++;
            }
        }

        return $date;
    }

    public function searchAllOrderAdjustmentHistoryExcel($coupons = [])
    {
        $date = [];
        if (count($coupons) > 0) {
            $i = 0;
            foreach ($coupons as $coupon) {

                $date[$i][] = $coupon->label;
                $date[$i][] = $coupon->order->order_no;
                $date[$i][] = $coupon->order->total;

                if ($coupon->order->status == 7) {
                    $date[$i][] = "退款中";
                } elseif ($coupon->order->status == 1) {
                    $date[$i][] = "待付款";
                } elseif ($coupon->order->status == 1) {
                    $date[$i][] = "待付款";
                } elseif ($coupon->order->status == 2) {
                    $date[$i][] = "待发货";
                } elseif ($coupon->order->status == 3) {
                    $date[$i][] = "配送中待收货";
                } elseif ($coupon->order->status == 4) {
                    $date[$i][] = "已收货待评价";
                } elseif ($coupon->order->status == 5) {
                    $date[$i][] = "已完成";
                } elseif ($coupon->order->status == 6) {
                    $date[$i][] = "已取消";
                }

                $userInfo = User::find($coupon->order->user_id);

                $date[$i][] = isset($userInfo->name) ? $userInfo->name : '';
                foreach ($coupon->created_at as $value) {
                    $date[$i][] = basename($value, "." . substr(strchr($value, '.'), 1));
                }

                unset($date[$i][6]);
                unset($date[$i][7]);

                $i++;
            }
        }

        return $date;
    }

    

   
    /**
     * 过滤活动规则
     *
     * @param $data
     *
     * @return array
     */
    public function filterDiscountRules($data)
    {

        foreach ($data as $key => $val) {
            if (!isset($val['type'])) {
                unset($data[$key]);
                continue;
            }

            if (isset($val['type']) AND !is_array($val) AND empty($val)) {
                unset($data[$key]);
                continue;
            }

            if ($val['type'] == 'contains_product' AND empty($val['value']['sku']) AND empty($val['value']['spu'])) {
                unset($data[$key]);
                continue;
            }

            if ($val['type'] == 'contains_category' AND (!isset($val['value']['items']) || count($val['value']['items']) == 0)) {
                unset($data[$key]);
                continue;
            }

            if ($val['type'] == 'contains_shops' AND count($val['value']['shop_id']) == 0) {
                unset($data[$key]);
                continue;
            }
        }

        if (count($data) == 0) {
            return [];
        }

        return $data;
    }

    /**
     * 保存活动/优惠券
     *
     * @param $base
     * @param $action
     * @param $rules
     * @param $coupon_base
     *
     * @return bool|static
     */
    public function saveData($base, $action, $rules, $coupon_base)
    {
        if ($id = request('id')) { //修改

            $discount = ElDiscount::find($id);
            $discount->fill($base);
            $discount->save();

            //action
            if ($actionData = ElDiscountAction::find(request('action_id'))) {
                if ($action['configuration']) {
                    $actionData->fill($action);
                    $actionData->save();
                } else {
                    $actionData->delete();
                }
            } elseif ($action['configuration']) {
                $action['discount_id'] = $discount->id;
                ElDiscountAction::create($action);
            }

            //delete rules
            $discount->discountRules()->delete();
        } else {
            $base['coupon_based'] = $coupon_base;
            $discount = ElDiscount::create($base);

            //action
            if ($action['configuration']) {
                $action['discount_id'] = $discount->id;
                ElDiscountAction::create($action);
            }
        }

        //rules
        $filterRules = $this->filterDiscountRules($rules);
        if (count($filterRules) == 0) {
            return false;
        }
        foreach ($filterRules as $key => $val) {
            $rulesData = [];
            $rulesData['discount_id'] = $discount->id;
            $rulesData['type'] = $val['type'];
            $rulesData['configuration'] = $val['value'];

            ElDiscountRule::create($rulesData);
        }

        return $discount;
    }
}
