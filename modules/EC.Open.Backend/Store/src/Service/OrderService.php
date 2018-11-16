<?php

namespace iBrand\EC\Open\Backend\Store\Service;

use iBrand\EC\Open\Backend\Store\Model\Order;
use iBrand\EC\Open\Backend\Store\Model\Refund;
use iBrand\EC\Open\Backend\Store\Model\SpecialType;
use iBrand\EC\Open\Backend\Store\Repositories\OrderRepository;
use Excel;
use iBrand\EC\Open\Backend\Store\Facades\ExcelExportsService;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsRepository;
use iBrand\EC\Open\Backend\Store\Repositories\ProductRepository;
use iBrand\EC\Open\Backend\Store\Repositories\BrandRepository;
use iBrand\EC\Open\Backend\Store\Model\User;
use iBrand\EC\Open\Backend\Store\Model\Product;
use iBrand\EC\Open\Backend\Store\Model\Goods;
use Maatwebsite\Excel\Classes\PHPExcel;
use ElementVip\Component\Point\Repository\PointRepository;

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
//                        if ($product && count($product)>0)
//                        {
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
//                        $date[$i][]=$goodsInfo;
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
                        $data[$i][] = $value->supplier ? $value->supplier->name : '';


                        $specialType = SpecialType::where('order_id', $item->id)->where('origin_type', 'suit')->first();
                        if ($specialType AND $suit = $specialType->suit) {
                            $data[$i][] = $item->order_type . '(' . $suit->title . ')';
                        } else {
                            $data[$i][] = $item->order_type;
                        }

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

                        if ($value->is_send == 1) {
                            $data[$i][] = '已发货';
                        } elseif ($item->distribution_status == 1 AND $value->is_send == 0) {
                            $data[$i][] = '已发货';
                        } else {
                            $data[$i][] = '未发货';
                        }

                        $data[$i][] = $item->total;

                        $data[$i][] = $item->created_at;
                        $data[$i][] = $item->pay_time;
                        $data[$i][] = $this->getRefundStatus($value->id);
                        $data[$i][] = $item->note;

                        $i++;
                    }
                }
            }
        }

        return $data;
    }

    protected function getRefundStatus($id)
    {
        $status = '';
        $refund = Refund::where('order_item_id', $id)->get();
        if (count($refund) > 0) {
            foreach ($refund as $item) {
                $status = $status . $item->status_text . '，';
            }
        }
        return $status;
    }

    /**
     * 判断订单售后状态
     *
     * @param Order $order
     *
     * @return bool
     */
    public function checkOrderRefund(Order $order)
    {
        $refunds = $order->refunds;
        if (count($refunds) > 0) {
            $filtered = $refunds->filter(function ($item) {
                return $item->status == 2 OR $item->status == 4 OR $item->status == 3;
            });
            if (count($filtered) == count($refunds)) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * 检测当前订单是否有item属于当前登录商家
     *
     * @param Order $order
     *
     * @return bool
     */
    public function checkOrderDeliver(Order $order)
    {
        $items = $order->items()->where('is_send', 0)->get()->toArray();
        $supplierIds = array_column($items, 'supplier_id');
        if (array_intersect(session('admin_supplier_id'), $supplierIds)) {
            return true;
        }

        return false;
    }
}
