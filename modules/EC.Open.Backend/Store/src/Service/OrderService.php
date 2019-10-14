<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Service;

use GuoJiangClub\Component\Point\Repository\PointRepository;
use GuoJiangClub\EC\Open\Backend\Member\Models\User;
use GuoJiangClub\EC\Open\Backend\Store\Model\Order;

use GuoJiangClub\EC\Open\Backend\Store\Model\SpecialType;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\OrderRepository;
use Excel;
use GuoJiangClub\EC\Open\Backend\Store\Facades\ExcelExportsService;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ProductRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\BrandRepository;
use GuoJiangClub\EC\Open\Backend\Store\Model\Product;
use GuoJiangClub\EC\Open\Backend\Store\Model\Goods;


class OrderService
{

    protected $orderRepository;
    protected $goodsRepository;
    protected $brandRepository;
    protected $productRepository;
    protected $pointRepository;

    public function __construct()
    {
        $this->goodsRepository = app(GoodsRepository::class);
        $this->orderRepository = app(OrderRepository::class);
        $this->brandRepository = app(BrandRepository::class);
        $this->productRepository = app(ProductRepository::class);
        $this->pointRepository = app(PointRepository::class);
    }

    /**获取搜索到的全部订单Excel数据
     *
     * @param       $name
     * @param array $orders
     * @param array $titles
     *
     * @return mixed
     */

    public function searchAllOrdersExcel($name, $orders = [], $titles = [])
    {
        $data = [];
        if (count($orders) > 0 && count($titles) > 0) {
            $i = 0;
            foreach ($orders as $item) {
                $goodsInfo = '';
                $goods_brand_name = '';
                $goods_brand = '';
                $point = $this->pointRepository->distributePercentage($item);
                foreach ($item->items as $value) {
                    if (isset($value)) {
                        $goods = [];
                        $item_meta = json_decode($value->item_meta, true);
                        $goods_name = isset($value->item_name) ? $value->item_name : '';
                        $goods_value = isset($item_meta->spec_text) ? $item_meta->spec_text : '';
                        $product = Product::find($value->item_id);

                        $sku = isset($product->sku) ? $product->sku : '';
                        if (isset($product->goods_id)) {
                            $goods = Goods::find($product->goods_id);
                        }

                        if (isset($goods) && count($goods)) {
                            $goods_brand = $this->brandRepository->find($goods->brand_id);
                            $goods_brand_name = $goods_brand->name;
                        }
                        $address_name = explode(' ', $item->address_name);
                        $user = User::find($item->user_id);

                        $data[$i][] = $item->order_no;
                        $data[$i][] = $goods_brand_name;
                        $data[$i][] = $item->registration_id != 0 ? '注册礼订单' : '商城订单';
                        $data[$i][] = $user ? $user->name : '';
                        $data[$i][] = $item->accept_name;
                        $data[$i][] = isset($address_name[0]) ? $address_name[0] : '';
                        $data[$i][] = isset($address_name[1]) ? $address_name[1] : '';
                        $data[$i][] = isset($address_name[2]) ? $address_name[2] : '';
                        $data[$i][] = isset($item->address) ? $item->address : '';
                        $data[$i][] = isset($item->mobile) ? $item->mobile : '';
                        $data[$i][] = isset($user) && isset($user->email) ? $user->email : '';
                        $data[$i][] = $goods_name;
                        $data[$i][] = $sku;
                        $data[$i][] = isset($goods->market_price) ? $goods->market_price : 0;
                        $data[$i][] = $value->unit_price;
                        $data[$i][] = $value->quantity;
                        $data[$i][] = $value->total;

                        $labels = '';
                        $discount = 0;
                        $point_discount = 0;
                        $label_list = [];
                        if (isset($item->adjustments) && count($item->adjustments)) {
                            $label = [];

                            foreach ($item->adjustments as $val) {
                                if ($val->origin_type != 'point') {
                                    $label_list[] = $val->label;
                                    $discount += (int)str_replace("-", "", $val->amount) / 100;
                                }
                            }
                            if (count($label_list)) {
                                $labels = implode($label_list);
                            } else {
                                $labels = '';
                            }

                            $data[$i][] = $labels;
                            $data[$i][] = $discount;
                        } else {
                            $data[$i][] = $labels;
                            $data[$i][] = $discount;
                        }

                        if (is_array($point)) {
                            foreach ($point as $val) {
                                if ($value->id == $val['item_id']) {
                                    $point_discount = $val['value'] / 100;
                                }
                            }
                        }
                        $data[$i][] = $point_discount;

                        $item->status = (int)$item->status;
                        if ($item->status == 7) {
                            $data[$i][] = "退款中";
                        } elseif ($item->status == 1) {
                            $data[$i][] = "待付款";
                        } elseif ($item->status == 2) {
                            $data[$i][] = "待发货";
                        } elseif ($item->status == 3) {
                            $data[$i][] = "配送中待收货";
                        } elseif ($item->status == 4) {
                            $data[$i][] = "已收货待评价";
                        } elseif ($item->status == 5) {
                            $data[$i][] = "已完成";
                        } elseif ($item->status == 6) {
                            $data[$i][] = "已取消";
                        } elseif ($item->status == 9) {
                            $data[$i][] = "已删除";
                        } else {
                            $data[$i][] = "";
                        }

                        $data[$i][] = $item->pay_status == 1 ? '已付款' : '未付款';
                        $data[$i][] = isset($item->PayTypeText) ? $item->PayTypeText : '';
                        $data[$i][] = isset($item->channel_no) ? $item->channel_no : '';
                        $data[$i][] = $item->distribution_status == 1 ? '已发货' : '未发货';
                        $data[$i][] = $item->total;
                        $data[$i][] = $item->created_at;
                        $data[$i][] = $item->pay_time;
                        $i++;
//                        }
                    }
                }
            }
        }

        return ExcelExportsService::createExcelExport($name, $data, $titles);
    }

    /**
     * 格式化订单数据，导出excel
     * @param $orders :订单数据
     * @return array
     */
    public function formatToExcelData($orders)
    {
        $data = [];

        if ($orders AND count($orders) > 0) {
            $i = 0;
            foreach ($orders as $item) {
                $goodsInfo = '';
                $goods_brand_name = '';
                $goods_brand = '';
                $point = $this->pointRepository->distributePercentage($item);
                foreach ($item->items as $value) {
                    if (isset($value)) {

                        $goods = [];
                        $item_meta = json_decode($value->item_meta, true);

                        $goods_name = isset($value->item_name) ? $value->item_name : '';
                        $goods_value = isset($item_meta['specs_text']) ? $item_meta['specs_text'] : '';

                        $product = Product::find($value->item_id);

                        $sku = isset($product->sku) ? $product->sku . "\t" : '';

                        if (isset($product->goods_id)) {
                            $goods = Goods::find($product->goods_id);
                        }

                        if (isset($goods) && count($goods)) {
                            $goods_brand = $this->brandRepository->find($goods->brand_id);
                            $goods_brand_name = $goods_brand->name;
                        }
                        $address_name = explode(' ', $item->address_name);
                        $user = User::find($item->user_id);

                        $data[$i][] = $item->order_no;
                        $data[$i][] = $goods_brand_name;


                        $data[$i][] = $user ? ($user->name ? $user->name : $user->mobile) : '';
                        $data[$i][] = $item->accept_name;
                        $data[$i][] = isset($address_name[0]) ? $address_name[0] : '';
                        $data[$i][] = isset($address_name[1]) ? $address_name[1] : '';
                        $data[$i][] = isset($address_name[2]) ? $address_name[2] : '';
                        $data[$i][] = isset($item->address) ? $item->address : '';
                        $data[$i][] = isset($item->mobile) ? $item->mobile : '';
                        $data[$i][] = isset($user) && isset($user->email) ? $user->email : '';
                        $data[$i][] = $goods_name;
                        $data[$i][] = $sku;
                        $data[$i][] = $goods_value;
                        $data[$i][] = isset($goods->market_price) ? $goods->market_price : 0;
                        $data[$i][] = $value->unit_price;
                        $data[$i][] = $value->quantity;
                        $data[$i][] = $value->total;

                        $labels = '';
                        $discount = 0;
                        $point_discount = 0;
                        $label_list = [];

                        if (isset($item->adjustments) && count($item->adjustments)) {
                            $label = [];

                            foreach ($item->adjustments as $val) {
                                if ($val->origin_type != 'point') {
                                    $label_list[] = $val->label;
                                    $discount += (int)str_replace("-", "", $val->amount) / 100;
                                }
                            }
                            if (count($label_list)) {
                                $labels = implode($label_list);
                            } else {
                                $labels = '';
                            }

                            $data[$i][] = $labels;
                            $data[$i][] = $discount;
                        } else {
                            $data[$i][] = $labels;
                            $data[$i][] = $discount;
                        }


                        if (is_array($point)) {
                            foreach ($point as $val) {
                                if ($value->id == $val['item_id']) {
                                    $point_discount = $val['value'] / 100;
                                }
                            }
                        }
                        $data[$i][] = $point_discount;
                        $data[$i][] = $item->status_text;
                        $data[$i][] = $item->pay_status == 1 ? '已付款' : '未付款';
                        $data[$i][] = isset($item->PayTypeText) ? $item->PayTypeText : '';
                        $data[$i][] = isset($item->channel_no) ? $item->channel_no : '';

                        if ($item->distribution_status == 1) {
                            $data[$i][] = '已发货';
                        } else {
                            $data[$i][] = '未发货';
                        }

                        $data[$i][] = $item->total;

                        $data[$i][] = $item->submit_time;
                        $data[$i][] = $item->pay_time;
                        $data[$i][] = $item->note;

                        $i++;
                    }
                }
            }
        }

        return $data;
    }
}
