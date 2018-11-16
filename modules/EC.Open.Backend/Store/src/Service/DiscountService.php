<?php

namespace iBrand\EC\Open\Backend\Store\Service;

use Carbon\Carbon;
use ElementVip\Component\Discount\Models\Discount;
use ElementVip\Shop\Core\Models\O2oDiscountRelation;
use iBrand\EC\Open\Backend\Store\Model\ElDiscount;
use iBrand\EC\Open\Backend\Store\Model\ElDiscountAction;
use iBrand\EC\Open\Backend\Store\Model\ElDiscountRule;
use iBrand\EC\Open\Backend\Store\Model\SingleDiscount;
use iBrand\EC\Open\Backend\Store\Model\SingleDiscountCondition;
use iBrand\EC\Open\Backend\Store\Repositories\ProductRepository;
use Excel;
use iBrand\EC\Open\Backend\Store\Model\User;
use iBrand\EC\Open\Backend\Store\Facades\ExcelExportsService;

class DiscountService
{
    protected $productRepository;
    protected $cache;

    public function __construct()
    {
        $this->productRepository = app(ProductRepository::class);
        $this->cache = cache();
    }

    /**
     * 处理单品折扣数据
     *
     * @param $data
     *
     * @return array
     */
    public function handleItemDiscountData($data)
    {
        if (!isset($data['_conditionValue'])) {
            return [[], []];
        }

        $value = [];
        $conditionArr = [];
        $discountConditionType = [];
        $conditionValue = $data['_conditionValue'];
        $delSku = isset($data['delID']) ? $data['delID'] : '';
        $delSkuArr = explode(',', ltrim($delSku, ','));  //删除的数据

        foreach ($data as $key => $val) {
            if (strpos($key, 'type_') !== false) {
                $discountConditionType[$key] = $val;    //促销优惠方式数据
            }
        }

        foreach ($discountConditionType as $key => $val) {
            foreach ($val as $k => $item) {
                if ($item) {
                    $value[$k][$key] = $item;
                }
            }
        }

        foreach ($conditionValue as $key => $val) {
            if (isset($value[$key]) AND $product = $this->productRepository->getProductBySku($val)) {
                $conditionArr[$key]['name'] = $val;
                $conditionArr[$key]['type'] = array_keys($value[$key])[0];
                $conditionArr[$key]['price'] = current($value[$key]);
                $conditionArr[$key]['value'] = $value[$key];
                $conditionArr[$key]['goods_id'] = $product->goods_id;
            }/*else{
                 $conditionArr[$key]['value'] = 0;
             }*/
        }

        return [$conditionArr, $delSkuArr];
    }

    /**
     * 单品折扣创建
     *
     * @param $data
     */
    /* public function updatePriceByDiscount($condition)
     {

         foreach ($condition as $key => $val) {
             $type = $val['value'];

             $product = $this->productRepository->getProductBySku($val['name']);

             if ($product) {
                 switch (key($type)) {
                     case 'type_cash':
                         $price = $type['type_cash'];
                         break;

                     case 'type_discount';
                         $price = $product->goods->market_price * ($type['type_discount']);
                 }


                 $this->productRepository->update(['sell_price' => $price], $product->id);
             }

         }

     }
    */

    /**
     * 删除sku折扣
     *
     * @param $delSku
     */
    /*public function delItemDiscountSku($delSku)
    {
        $product = $this->productRepository->findWhereIn('sku', $delSku);

        foreach ($product as $key => $val) {
            $val->sell_price = $val->goods->sell_price;
            $val->save();
        }
    }*/

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
                /*foreach ($coupon->created_at as $value) {
                    $date[$i][] = basename($value, "." . substr(strchr($value, '.'), 1));
                }*/

                $date[$i][] = $coupon->used_at;

                $date[$i][] = Discount::find($coupon->discount_id)->title;
//                foreach($coupon['relations'] as $item){
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

//                }

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
//                foreach($coupon['relations'] as $item){
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

                $userInfo=User::find($coupon->order->user_id);

                $date[$i][] = isset($userInfo->name)?$userInfo->name:'';

//                }

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

    public function cacheSingleDiscount($discount_id)
    {
        $discount = SingleDiscount::find($discount_id);
        $diffDiscount = SingleDiscount::where('id', '<>', $discount_id)
            ->where('status', 1)
            ->where('ends_at', '>', Carbon::now())
            ->count();

        if ($discount->status == 1) {
            SingleDiscount::where('id', '<>', $discount_id)
                ->where('status', 1)
                ->update(['status' => 0]);

            $end = new Carbon($discount->ends_at);
            $expressAt = $end->diffInMinutes(Carbon::now());
            $data = $this->setCacheData($discount);

            $this->cache->forget('singleDiscount');
            $this->cache->put('singleDiscount', $data, $expressAt + 1);
        } elseif ($diffDiscount == 0 AND $discount->status == 0) {
            $this->cache->forget('singleDiscount');
            $this->cache->forever('singleDiscount', []);
        }
    }

    protected function setCacheData($discount)
    {
        $condition = collect();
        $data = [];
        if (count($singleCondition = SingleDiscountCondition::where('single_discount_id', $discount->id)->get()) > 0) {
            /*foreach ($singleCondition as $key => $item) {
                $condition->push(['sku'=>$item->name,'type'=>$item->type,'value'=>$item->price]);
            }*/

            $data = $discount;
            $data['condition'] = $singleCondition;

//            $data = $discount->toArray();
//            $data['condition'] = $condition;
        }

        return $data;
    }

    /**
     * 统计单品折扣信息
     *
     * @param $discount_id
     *
     * @return array
     */
    public function calculationDiscount($discount_id)
    {
        $discount = SingleDiscount::find($discount_id);

        $filterSection = [
            0 => [0, 1],
            1 => [1, 2],
            2 => [2, 3],
            3 => [3, 4],
            4 => [4, 5],
            5 => [5, 11],
        ];
        $count = [];
        foreach ($filterSection as $value) {
            $filtered = $discount->hasManyCondition->filter(function ($item) use ($value) {
                /*if (isset($item->value['type_discount'])) {
                    return $item->value['type_discount'] >= $value[0] AND $item->value['type_discount'] < $value[1];
                }

                if (isset($item->value['type_cash']) AND $product = $this->productRepository->getProductBySku($item->name)) {

                    if ($market = $product->market_price) {
                        return (($item->value['type_cash'] / $market) * 10 >= $value[0] AND ($item->value['type_cash'] / $market) * 10 < $value[1]);
                    } else {
                        $market = $product->goods->market_price;
                        return (($item->value['type_cash'] / $market) * 10 >= $value[0] AND ($item->value['type_cash'] / $market) * 10 < $value[1]);
                    }

                }*/
                if ($item->type == 'type_discount') {
                    return $item->price >= $value[0] AND $item->price < $value[1];
                }

                if ($item->type == 'type_cash' AND $product = $this->productRepository->getProductBySku($item->name)) {

                    if ($market = $product->market_price > 0) {
                        return (($item->price / $market) * 10 >= $value[0] AND ($item->price / $market) * 10 < $value[1]);
                    } else {
                        $market = $product->goods->market_price;

                        return (($item->price / $market) * 10 >= $value[0] AND ($item->price / $market) * 10 < $value[1]);
                    }
                }
            })->count();
            $count[] = $filtered;
        }

        return $count;
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
            //return ['status' => false, 'message' => '请至少设置一种规则'];
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

            //point action
            if ($pointAction = ElDiscountAction::find(request('point_action_id'))) {
                if (request('point-action')['configuration']) {
                    $pointAction->fill(request('point-action'));
                    $pointAction->save();
                } else {
                    $pointAction->delete();
                }
            } elseif (request('point-action')['configuration']) {
                $addPointAction = request('point-action');
                $addPointAction['discount_id'] = $discount->id;
                ElDiscountAction::create($addPointAction);
            }

            if (isset($rules[6]) && !empty($rules[6]['value'])) {
                $shop_ids = [];
                if (isset($rules[6]['value']['shop_id']) && !empty($rules[6]['value']['shop_id'])) {
                    $shop_ids = explode(',', $rules[6]['value']['shop_id']);
                }

                $shop_ids_original = [];
                if (isset($rules[6]['value']['shop_id_original']) && !empty($rules[6]['value']['shop_id_original'])) {
                    $shop_ids_original = explode(',', $rules[6]['value']['shop_id_original']);
                }

                if (!empty($shop_ids)) {
                    foreach ($shop_ids as $shop_id) {
                        if (!empty($shop_ids_original) && in_array($shop_id, $shop_ids_original)) {
                            continue;
                        }

                        O2oDiscountRelation::create([
                            'discount_id' => $discount->id,
                            'shop_id' => $shop_id,
                        ]);
                    }
                }

                if (!empty($shop_ids_original)) {
                    foreach ($shop_ids_original as $shop_id_original) {
                        if ((!empty($shop_ids) && !in_array($shop_id_original, $shop_ids)) || !isset($rules[6]['type']) || $rules[6]['type'] != 'contains_shops') {
                            O2oDiscountRelation::where('shop_id', $shop_id_original)->where('discount_id', $id)->delete();
                        }
                    }
                }
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

            if (request('point-action')['configuration']) {
                $addPointAction = request('point-action');
                $addPointAction['discount_id'] = $discount->id;
                ElDiscountAction::create($addPointAction);
            }

            if (isset($rules[6]) && !empty($rules[6]['value'])) {
                $shop_ids = explode(',', $rules[6]['value']['shop_id']);
                if (is_array($shop_ids) && !empty($shop_ids)) {
                    foreach ($shop_ids as $shop_id) {
                        if ($shop_id) {
                            O2oDiscountRelation::create([
                                'discount_id' => $discount->id,
                                'shop_id' => $shop_id,
                            ]);
                        }
                    }
                }
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

    public function getSingleDiscountPaginate($discount_id, $limit)
    {
        $conditions = SingleDiscountCondition::where('single_discount_id', $discount_id)->paginate($limit);
        $lastPage = $conditions->lastPage();

        return [$conditions, $lastPage];
    }

}
