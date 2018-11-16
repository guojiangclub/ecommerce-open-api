<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use Cache;
use Carbon\Carbon;
use ElementVip\Component\Order\Models\Comment;
use ElementVip\Component\Order\Models\Order;
use ElementVip\Component\Payment\Services\PaymentService;
use ElementVip\Component\Point\Model\Point;
use ElementVip\Component\Point\Repository\PointRepository;
use iBrand\Component\Product\Models\Goods;
use iBrand\Component\Product\Models\Registration;
use ElementVip\Component\Registration\Models\ErpDeliveriesData;
use ElementVip\Component\Setting\Models\SystemSetting;
use ElementVip\Component\User\Models\Group;
use ElementVip\Component\User\Models\User;
use ElementVip\Member\Backend\Models\Card;
use iBrand\EC\Open\Backend\Store\Model\Attribute;
use iBrand\EC\Open\Backend\Store\Model\AttributeValue;
use iBrand\EC\Open\Backend\Store\Model\GoodsAttr;
use iBrand\EC\Open\Backend\Store\Model\Product;
use iBrand\EC\Open\Backend\Store\Model\SpecsValue;

use iBrand\EC\Open\Backend\Store\Repositories\CategoryRepository;
use iBrand\EC\Open\Backend\Store\Repositories\SpecRepository;
use App\Http\Controllers\Controller;

use DB;

//use ElementVip\TNF\Core\Tnf;
//use ElementVip\TNF\Core\Models\CardVipCode;
//use ElementVip\TNF\Core\Services\TnfService;
use ElementVip\Wechat\Server\Wx\MiniGetWxaCode;
use Illuminate\Support\Facades\Artisan;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class DataController extends Controller
{
    protected $specRepository;
    protected $tnf;
    protected $tnfService;
    protected $categoryRepository;
    private $point;
    protected $miniGetWxaCode;

    public function __construct(SpecRepository $specRepository,
        // Tnf $tnf,
        //  TnfService $tnfService,
                                PointRepository $pointRepository,
                                CategoryRepository $categoryRepository,
                                MiniGetWxaCode $miniGetWxaCode)
    {
        $this->specRepository = $specRepository;
        // $this->tnf = $tnf;
        //  $this->tnfService = $tnfService;
        $this->point = $pointRepository;
        $this->categoryRepository = $categoryRepository;
        $this->miniGetWxaCode = $miniGetWxaCode;
    }

    public function install()
    {
        return Artisan::call('dmp-store:install');
    }

    public function migrate()
    {
        return Artisan::call('migrate');
    }

    /**
     * 处理模型属性值数据
     */
    public function handleAttributeValue()
    {
        $attribute = DB::table('el_goods_attribute')->where('type', '=', 1)->get();
        foreach ($attribute as $item) {
            $valueList = explode(',', ltrim($item->value, ','));

            foreach ($valueList as $value) {
                $data = [
                    'attribute_id' => $item->id,
                    'name' => $value
                ];

                if ($attr = AttributeValue::create($data)) {
                    echo '成功处理第' . $attr->id . '条数据<br>';
                }
            }

        }

        echo '模型属性值数据处理完成';

    }

    /**
     * 处理规格表和规格值表
     */
    public function handleSpec()
    {
        $specs = DB::table('el_goods_spec')->get();

        foreach ($specs as $item) {

            $value = json_decode($item->value);
            $extent = json_decode($item->extent); //rgb
            $extent2 = json_decode($item->extent2); //color

            if (count($value)) {
                foreach ($value as $key => $val) {
                    if ($item->id == 2)  //颜色
                    {
                        $valueData = [
                            'spec_id' => $item->id,
                            'name' => $val,
                            'rgb' => $extent[$key],
                            'color' => $extent2[$key]
                        ];
                    } else {
                        $valueData = [
                            'spec_id' => $item->id,
                            'name' => $val
                        ];
                    }

                    SpecsValue::create($valueData);
                }
            }

        }

        echo '规格数据处理完成';
    }


    /**
     * 处理商品属性表 el_goods_attribute_relation
     */
    public function handleGoodsAttribute()
    {
        $goodsAttr = GoodsAttr::all();

        foreach ($goodsAttr as $item) {
            $attribute = Attribute::find($item->attribute_id);

            if ($attribute->type == 1) {
                foreach ($attribute->values as $val) {
                    if ($val->name == $item->attribute_value) {
                        $item->attribute_value_id = $val->id;
                        $item->attribute_value = '';
                        $item->save();
                    }
                }
            }

        }

    }

    /**
     * 处理商品规格中间表 el_goods_spec_relation
     * update el_goods_spec_relation a set spec_value_id = (SELECT id from el_goods_specs_value b where a.spec_value = b.name AND a.spec_id = b.spec_id )
     * 以上sql可不执行
     */

    public function handleGoodsSpecRelation()
    {
        /*$specs = DB::table('el_goods_spec_relation')->get();
        foreach ($specs as $item) {
            $specArr = explode(',', $item->spec_value);
            foreach ($specArr as $key => $val) {
                $ids = DB::select('SELECT id from el_goods_specs_value where name = "' . $val . '" AND spec_id = ' . $item->spec_id);
                foreach ($ids as $k => $v) {
                    $id = $v->id;
                }

                $data = [$item->goods_id, $item->spec_id, $id];

                DB::insert('insert into el_goods_spec_relation (goods_id, spec_id,spec_value_id) values (?,?,?)', $data);
            }
            DB::delete('delete from el_goods_spec_relation where id=' . $item->id);

        }*/

        //处理悦户外规格图片
        $specs = DB::table('el_goods_spec_relation')->where('spec_id', 2)->get();
        foreach ($specs as $item) {

            $product = Product::where('goods_id', $item->goods_id)->get();
            if (count($product) > 0) {
                //dd($product);
                $filtered = $product->filter(function ($value, $key) use ($item) {
                    return in_array($item->spec_value_id, $value->specID);
                })->first();


                if ($filtered) {
                    $photo = DB::table('el_goods_photo')->where('sku', $filtered->sku)->first();
                    if ($photo) {
                        DB::table('el_goods_spec_relation')
                            ->where('id', $item->id)
                            ->update(['img' => $photo->url]);
                    }

                }
            }


        }
    }

    /**
     *  处理货品表 el_goods_product
     *  update el_goods set store_nums  = store
     *  处理之后需要运行上述sql
     */
    public function handleProduct()
    {
        $product = Product::all();
        foreach ($product as $item) {
            $spec = json_decode($item->spec_array);
            if ($spec) {
                $specIDs = [];
                foreach ($spec as $key => $val) {
                    $specValue = SpecsValue::where('name', $val->value)->first();
                    if ($specValue) {
                        array_push($specIDs, $specValue->id);
                    }
                }

                $item->specID = $specIDs;
                if ($item->save()) {
                    echo 'product id = ' . $item->id . ' 处理成功';
                } else {
                    echo 'product id = ' . $item->id . ' 处理失败';
                }
            }


        }
    }


    /**
     * 处理规格图片路径
     */
    public function handleSpecImg()
    {
        $ids = [2619, 2607, 2623, 2621, 2620, 2618, 2617, 2612, 2607, 2602, 2590, 1939, 2622];
        $specs = DB::table('el_goods_spec_relation')->whereIn('goods_id', $ids)->where('spec_id', 2)->get();

        foreach ($specs as $key => $item) {
            if ($item->img AND !strpos($item->img, 'admin.tnf.ibrand.cc') AND !strpos($item->img, 'tnf-equipment')) {
                $img = 'http://admin.tnf.ibrand.cc' . $item->img;

                DB::table('el_goods_spec_relation')
                    ->where('id', $item->id)
                    ->update(['img' => $img]);

                echo 'el_goods_spec_relation ID： ' . $item->id . '  SKU图片处理成功<br>';
            }
        }

    }

    public function updateUserGrade()
    {
        if (empty(request('user_id')) OR empty('group')) {
            return '参数错误';
        }
        if ($user = User::find(request('user_id')) AND $group = Group::find(request('group'))) {
            $user->group_id = $group->id;
            $user->save();
            return '修改正确';
        } else {
            return '未找到数据';
        }
    }

    public function auto()
    {
        $goods = Goods::find(2580);
        $goods->calculateStock();
        $goods->save();
        return $goods->is_del;
    }

    public function fixFreeOrder()
    {
        $orders = Order::where('total', 0)->where('count', 0)->where('status', '>', 0)->get();
        foreach ($orders as $order) {
            $order->status = 6;
            $order->pay_status = 0;
            $order->save();
        }

        return $orders;
    }

    public function fixGoodsStore()
    {
        $goodses = Goods::where('is_del', 0)->get();
        $index = 0;
        foreach ($goodses as $goods) {
            if (($allProductStock = $goods->products()->sum('store_nums')) > 0) {
                $goods->store_nums = $allProductStock;
                $goods->save();
                $index++;
            }
        }

        return '此次修复了' . $index . '件商品库存';
    }

    public function fixGoodsInsale()
    {
        $goodsList = Goods::where('is_del', 0)->get();
        $index = 0;
        foreach ($goodsList as $goods) {
            if ($allProductStock = $goods->products()->max('is_show') == 0) {
                $goods->is_del = 2;
                $goods->save();
                $index++;
            }
        }
        return '自动下架:' . $index;
    }

    public function fixCommunityPointBug()
    {
        $results = DB::table('el_point')
            ->select('user_id', 'action', 'item_id', DB::raw('count(*) as count'))
            ->groupBy('user_id', 'action', 'item_id')
            ->whereIn('action', ['publish_activity', 'publish_story', 'publish_micro_story', 'publish_question'])
            ->havingRaw('count(*) > 1')
            ->get();

        foreach ($results as $result) {
            $point = Point::where(['user_id' => $result->user_id, 'action' => $result->action, 'item_id' => $result->item_id])
                ->orderBy('created_at', 'asc')->first();

            \Log::info(Point::where(['user_id' => $result->user_id, 'action' => $result->action, 'item_id' => $result->item_id])
                ->where('id', '<>', $point->id)->delete());
        }
    }

    public function fixGoodsPrice()
    {
        $goodses = Goods::where('is_del', 0)->get();
        $index = 0;
        foreach ($goodses as $goods) {
            if (($min_price = $goods->products()->min('sell_price')) > 0) {
                $goods->min_price = $min_price;
            }
            if (($max_price = $goods->products()->max('sell_price')) > 0) {
                $goods->max_price = $max_price;
            }

            if ($min_price > 0 || $max_price > 0) {
                $goods->save();
                $index++;
            }
        }

        return '此次修复了' . $index . '件商品价格';
    }


    //处理悦户外INPUT属性数据
    public function handleInputNewAttribute()
    {
        $inputType = DB::table('el_goods_attribute')->where('type', 2)->where('model_id', '<>', 0)->select('name')->distinct()->get();
        // dd($inputType);
        foreach ($inputType as $item) {
            if ($item->name != '年份') {
                /*创建新的公用属性*/
                $attrData = [
                    'model_id' => 0,
                    'type' => 2,
                    'name' => $item->name,
                    'value' => '',
                    'is_search' => 1,
                    'is_chart' => 0
                ];
                $attribute = Attribute::create($attrData);

                /*找出name为当前name的attribute的ID*/
                $attributes = DB::table('el_goods_attribute')
                    ->where('type', 2)
                    ->where('model_id', '<>', 0)
                    ->where('name', $item->name)
                    ->select(['id', 'model_id'])->get();

                $attributeIds = [];
                foreach ($attributes as $val) {
                    array_push($attributeIds, $val->id);

                    DB::table('el_model_attribute_relation')->insert([
                        'model_id' => $val->model_id,
                        'attribute_id' => $attribute->id
                    ]);
                }

                if (count($attributeIds) > 0) {
                    /*更新el_goods_attribute_relation的attribute_id为最新的公用属性ID*/
                    DB::table('el_goods_attribute_relation')->whereIn('attribute_id', $attributeIds)->update(['attribute_id' => $attribute->id]);
                }
            }


        }
    }


    //处理悦户外select属性数据
    public function handleNewAttribute()
    {
        $selectType = DB::table('el_goods_attribute')
            ->where('type', 1)
            ->where('model_id', '<>', 0)
            ->where('name', '<>', '产地')
            ->select('name')
            ->distinct()->get();

        //dd($selectType);

        foreach ($selectType as $item) {
            $selectAttribute = DB::table('el_goods_attribute')
                ->where('name', $item->name)
                ->where('model_id', '<>', 0)
                ->orderBy('id', 'desc')->first();

            $attrData = [
                'model_id' => 0,
                'type' => 1,
                'name' => $selectAttribute->name,
                'value' => explode(',', ltrim($selectAttribute->value, ",")),
                'is_search' => 1,
                'is_chart' => 1
            ];

            $attribute = Attribute::create($attrData);

            $name = $this->getAttrValue($selectAttribute->value);
            $attribute->values()->createMany($name);


            /*处理el_model_attribute_relation新数据*/
            $attributes = DB::table('el_goods_attribute')
                ->where('type', 1)
                ->where('model_id', '<>', 0)
                ->where('name', $item->name)
                ->select(['id', 'model_id'])->get();
            $attributeIds = [];
            foreach ($attributes as $val) {
                array_push($attributeIds, $val->id);
                DB::table('el_model_attribute_relation')->insert([
                    'model_id' => $val->model_id,
                    'attribute_id' => $attribute->id
                ]);
            }

            if (count($attributeIds) > 0) {
                /*更新el_goods_attribute_relation的attribute_id为最新的公用属性ID*/
                DB::table('el_goods_attribute_relation')->whereIn('attribute_id', $attributeIds)->update(['attribute_id' => $attribute->id]);

                $selectAttributeValue = DB::table('el_goods_attribute_relation')->where('attribute_value_id', '<>', 0)->get();
                foreach ($selectAttributeValue as $val) {
                    $oldAttrValue = DB::table('el_goods_attribute_value')->where('id', $val->attribute_value_id)->first();

                    if ($oldAttrValue->name == 'OUTDOOR LIFESTYLE') {
                        $str = '户外生活系列';
                    } elseif ($oldAttrValue->name == 'MOUNTAIN') {
                        $str = '登山系列';
                    } elseif ($oldAttrValue->name == 'TREKKING') {
                        $str = '徒步系列';
                    } elseif ($oldAttrValue->name == 'INTENSE') {
                        $str = '滑雪系列';
                    } elseif ($oldAttrValue->name == 'ACCESSORIES') {
                        $str = '配件系列';
                    } else {
                        $str = preg_replace('|[a-zA-Z]+|', '', $oldAttrValue->name);
                        $str = trim(str_replace('|', '', $str));
                    }

                    $newAttrValue = DB::table('el_goods_attribute_value')->where('name', $str)->orderBy('id', 'desc')->first();

                    DB::table('el_goods_attribute_relation')
                        ->where('id', $val->id)
                        ->update(['attribute_value_id' => $newAttrValue->id, 'attribute_value' => $newAttrValue->name]);

                }

            }


        }


    }

    protected function getAttrValue($value)
    {
        $arr = explode(',', $value);
        $name = [];
        foreach ($arr as $val) {
            $name[]['name'] = $val;
        }
        return $name;
    }

    public function deleteOldAttribute()
    {
        DB::table('el_goods_attribute')->where('model_id', '<>', 0)->delete();
    }

    /**
     * 将order数据转移到offline_order
     */
    public function moveOrderToOfflineOrder()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 100;

        $message = '';

        $orders = DB::table('orders')->paginate($limit);

        if (count($orders) > 0) {
            foreach ($orders as $item) {
                $order = [
                    'order_no' => $item->order_no,
                    'user_id' => $item->user_id,
                    'pay_type' => $item->pay_type,
                    'status' => 5,
                    'pay_status' => 1,
                    'distribution_status' => 1,
                    'distribution' => 0,
                    'count' => $item->count,
                    'accept_name' => $item->accept_name,
                    'mobile' => $item->mobile,
                    'payable_amount' => $item->payable_amount,
                    'real_amount' => $item->real_amount,
                    'order_amount' => $item->order_amount,
                    'coupon_item' => $item->coupon_item,
                    'completion_time' => $item->completion_time,
                    'accept_time' => $item->accept_time,
                    'point' => $item->point,
                    'note' => $item->note,
                    'shop_name' => $item->shop_name,
                    'created_at' => $item->created_at == '0000-00-00 00:00:00' ? $item->updated_at : $item->created_at,
                    'updated_at' => $item->updated_at
                ];
                $order_id = DB::table('el_offline_order')->insertGetId($order);

                $goods = DB::table('order_goods')->where('order_id', $item->id)->get();
                foreach ($goods as $value) {
                    $orderGoods = [
                        'order_id' => $order_id,
                        'goods_name' => $value->goods_name,
                        'goods_price' => $value->goods_price,
                        'goods_nums' => $value->goods_nums,
                        'goods_array' => $value->goods_array,
                        'is_send' => 1,
                        'created_at' => $value->created_at == '0000-00-00 00:00:00' ? $value->updated_at : $value->created_at,
                        'updated_at' => $value->updated_at
                    ];
                    DB::table('el_offline_order_goods')->insert($orderGoods);
                }

            }
            $msg = '订单: ' . $item->order_no . '转移成功<br>';
            \Log::info($msg);
            $message = $message . $msg;
        } else {
            $message = '全部处理完成';
        }

        $interval = 5;
        $url_bit = route('admin.data.moveOrderToOfflineOrder', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));

    }

    /**
     *  处理同一个手机多个卡号，删除其中一个卡号
     */
    public function handleNumberForUser()
    {
//        $msg = '';
//        $cards = DB::table('el_card')
//            ->select('*')
//            ->whereIn('mobile', function ($query) {
//                $query->select('mobile')
//                    ->from('el_card')
//                    ->groupBy('mobile')
//                    ->havingRaw('count(mobile) > 1');
//            })->get();
//
//        $grouped = $cards->groupBy('user_id');
//
//        foreach ($grouped as $key => $value) {
//            if (count($value) > 1) {
//                $count = count($value) - 1;
//                foreach ($value as $k => $item) {
//                    if (($item->name OR $k == $count) AND $item->id != 1306) {
//                        continue;
//                    }
//
//                    $card = Card::find($item->id);
//                    $card->delete();
//                    CardVipCode::where('card_id', $card->id)->delete();
//
//                    $msg = $msg . '====' . json_encode($card);
//
//                    echo '已删除<br>';
//                }
//            }
//        }
//
//        \Log::info('删除的卡号信息：' . $msg);
    }




//1、根据el_card数据的number，查询已经导入的VIP_REPORT数据，将查询结果的VIP_CODE_IN_TTPOS的值写入到card_code表
    //2、删除el_point表 type=offline, action=goods记录，运行SQL语句:delete from el_point where type=offline and action=goods
    //3、用户表减去线下订单消费金额，处理用户等级
    //4、处理线下订单数据问题
    //5、根据el_point表更新用户integral 和 available_integral积分值,运行SQL语句:
    //update el_user as a set integral = (select sum(value) from el_point as b   where b.user_id=a.id and b.type=default)
    //update el_user as a set available_integral = (select sum(value) from el_point as b   where b.user_id=a.id and b.type=default and b.status=1)


    //问题：
    //1、处理完4之后，出现部分用户积分总额为负数，因为：1、用户线下订单获得的积分比使用掉的积分少 2、年前系统积分抵扣问题
    //2、transcation表中，transcation_date值不是时间格式问题

    /**
     * 暂存用户等级有变化的uid
     * 删除目前所有的el_card_vip_code记录
     */
    public function logGradeUser()
    {

        DB::table('el_card_vip_code')->delete();
        DB::table('transaction_report')->update(['status' => 0]);

        $result = DB::table('orders')
            ->select('user_id')
            ->groupBy('user_id')
            ->get()->toArray();

        $uid = '';
        foreach ($result as $item) {
            $uid = $item->user_id . ',' . $uid;
        }
        \Log::info('用户等级有变化的uid：' . $uid);
    }

    /**
     * 1、根据el_card数据，查询已经导入的VIP_REPORT数据，根据VIP_ID查询VIP_CODE_IN_TTPOS，将VIP_CODE_IN_TTPOS的值写入到card_code表
     * max limit = 200
     */
    public function AddVipCodeInTtpos()
    {
//        $page = request('page') ? request('page') + 1 : 2;
//        $limit = request('limit') ? request('limit') : 15;
//        $cards = Card::paginate($limit);
//        $message = '';
//
//        if (count($cards) > 0) {
//            foreach ($cards as $item) {
//                if (str_contains($item->number, [302016, 302017])) {
//                    $vip_code = substr_replace($item->number, '', '1', '1');
//                    if (!CardVipCode::where('vip_code_in_ttpos', $vip_code)->first()) {
//                        CardVipCode::create(['card_id' => $item->id, 'vip_code_in_ttpos' => $vip_code]);
//                    }
//                }
//
//                $code = DB::table('vip_report')
//                    /*->where(function ($query) use ($item) {
//                        $query->where('VIP_ID', $item->number)
//                            ->orWhere('Mobile_Phone_Number', $item->mobile);
//                    })*/
//                    ->where('VIP_ID', $item->number)
//                    ->whereNotIn('VIP_Code_In_TTPOS', function ($query) {
//                        $query->select('vip_code_in_ttpos')
//                            ->from('el_card_vip_code');
//                    })->get();
//
//                if (count($code) == 0) {
//                    $code = DB::table('vip_report')
//                        ->where('Mobile_Phone_Number', $item->mobile)
//                        ->whereNotIn('VIP_Code_In_TTPOS', function ($query) {
//                            $query->select('vip_code_in_ttpos')
//                                ->from('el_card_vip_code');
//                        })->get();
//                }
//
//
//                if (count($code) > 0) {
//                    foreach ($code as $value) {
//                        if (!CardVipCode::where('vip_code_in_ttpos', $value->VIP_Code_In_TTPOS)->first()) {
//                            CardVipCode::create(['card_id' => $item->id, 'vip_code_in_ttpos' => $value->VIP_Code_In_TTPOS]);
//                        }
//
//                    }
//                }
//
//                $msg = '会员ID : ' . $item->user_id . ' ,会员编号: ' . $item->number . ' 处理完成<br>';
//                \Log::info($msg);
//                $message = $message . $msg;
//            }
//
//        } else {
//            $message = '全部处理完成';
//        }
//
//        $interval = 5;
//        $url_bit = route('admin.data.AddVipCodeInTtpos', ['page' => $page, 'limit' => $limit]);
//
//        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));

    }

    /**
     * 3.1 、处理用户等级
     * 更新el_user表current_exp，并且更新用户等级
     * max limit = 500
     */
    public function handleUserGrade()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 15;

        $message = '';
        $result = DB::table('orders')
            ->select('user_id', DB::raw('SUM(order_amount) as total'))
            ->groupBy('user_id')
            ->where('user_id', '>', 0)
            ->paginate($limit);

        if (count($result) > 0) {
            foreach ($result as $item) {
                $this->updateUserLevel($item->user_id, -$item->total);

                DB::table('orders')
                    ->where('user_id', $item->user_id)
                    ->update(['user_id' => 0]);

                $msg = '用户' . $item->user_id . ' 等级处理完毕<br>';
                \Log::info($msg);
                $message = $message . $msg;
            }
        } else {
            $message = '全部处理完成';
        }


        $interval = 5;
        $url_bit = route('admin.data.handleUserGrade', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    /**
     * 3.2 、处理用户等级,el_offline_order
     * 更新el_user表current_exp，并且更新用户等级
     * max limit = 500
     */
    public function handleUserGrade2()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 15;
        if (session('handleUserGrade2')) {
            session()->forget('handleUserGrade2');
            return;
        }

        $message = '';
        $result = DB::table('el_offline_order')
            ->select('user_id', DB::raw('SUM(order_amount) as total'))
            ->groupBy('user_id')
            ->where('user_id', '>', 0)
            ->paginate($limit);

        if (count($result) > 0) {
            foreach ($result as $item) {
                $this->updateUserLevel($item->user_id, -$item->total);

                DB::table('el_offline_order')
                    ->where('user_id', $item->user_id)
                    ->update(['user_id' => 0]);


                $msg = '用户' . $item->user_id . ' 等级处理完毕<br>';
                \Log::info($msg);
                $message = $message . $msg;
            }
        } else {
            session(['handleUserGrade2' => 'complete']);
            DB::table('el_offline_order_goods')->delete();
            DB::table('el_offline_order')->delete();
            $message = '全部处理完成';
        }


        $interval = 5;
        $url_bit = route('admin.data.handleUserGrade2', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    private function updateUserLevel($user_id, $amount = 0)
    {
        $user = User::find($user_id);

        $user->current_exp = round($user->current_exp + $amount);
        $user->save();

        if ($upGroup = Group::where('min', '<', $user->current_exp)->orderBy('grade', 'desc')->first()) {
            $user->group_id = $upGroup->id;
            $user->save();
        }

    }

    /**
     * 4、处理线下订单数据问题
     * max limit 5
     */
    public function handleOfflineOrder()
    {
//        $page = request('page') ? request('page') + 1 : 2;
//        $limit = request('limit') ? request('limit') : 15;
//        $message = '';
//
//        $vip_users = DB::table('vip_report')->whereIn('Mobile_Phone_Number', function ($query) {
//            $query->select('mobile')->from('el_user');
//        })->paginate($limit);
//
//        if (count($vip_users) > 0) {
//            foreach ($vip_users as $item) {
//
//                if ($user = User::where('mobile', $item->Mobile_Phone_Number)->first() AND $card = $user->card) {
//
//                    $orders = DB::table('transaction_report')
//                        ->whereIn('VIP_Code_in_TTPOS', function ($query) use ($card) {
//                            $query->select('vip_code_in_ttpos')
//                                ->from('el_card_vip_code')
//                                ->where('card_id', $card->id);
//                        })
//                        ->where('SKU_Ops_Sales_Amount', '>', 0)
//                        ->where('status', 0)
//                        ->get();
//
//                    if (count($orders) > 0) {
//                        $this->tnf->handleOfflineOrder($orders, $this->userHandle($user));
//
//                        $msg = '用户' . $user->id . '线下订单处理完毕<br>';
//                        \Log::info($msg);
//                        $message = $message . $msg;
//                    }
//                }
//            }
//
//        } else {
//            $message = '全部处理完成';
//        }
//
//        $interval = 5;
//        $url_bit = route('admin.data.handleOfflineOrder', ['page' => $page, 'limit' => $limit]);
//
//        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));

    }

    private function userHandle($user)
    {
        $userinfo['uid'] = $user->id;
        $userinfo['mobile'] = $user->mobile;
        $userinfo['nick_name'] = $user->name ? $user->name : $user->nick_name;
        $userinfo['open_id'] = '';
        return $userinfo;

    }

    public function processSiteSetting()
    {
        //menu_list
        SystemSetting::where('key', 'menu_list')->delete();
        SystemSetting::where('key', 'store_logo')->delete();
        SystemSetting::where('key', 'store_name')->delete();

    }

    public function ddApp()
    {
        return dd(app());
    }

    public function ddSchedule()
    {
        return dd(app('ElementVip\ScheduleList')->get());
    }

    public function runSql()
    {
        $result = '';
        if ($sql = request('sql') AND $type = request('type')) {
            try {
                DB::beginTransaction();
                switch ($type) {
                    case 'insert':
                        $result = DB::insert($sql);
                        break;
                    case 'delete':
                        $result = DB::delete($sql);
                        break;
                    case 'update':
                        $result = DB::update($sql);
                        break;
                    case 'select':
                        $result = DB::select($sql);
                        break;
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                \Log::info($exception);
                $result = $exception;
                return view("store-backend::data.run_sql", compact('result'));
            }

            $result = json_encode($result);
            return view("store-backend::data.run_sql", compact('result'));
        }

        return view("store-backend::data.run_sql", compact('result'));
    }


    /**
     * 处理分多喜商品注册关联drupal订单ID数据问题
     * @return mixed
     */
    public function handleFuntasyRegistrationData()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 100;
        $message = '';

        $drupalData = DB::table('funtasy_register_order')->paginate($limit);

        $log = new Logger('registration');
        $log->pushHandler(
            new StreamHandler(
                storage_path('logs/registration.log'),
                Logger::INFO
            )
        );

        if (count($drupalData) > 0) {
            foreach ($drupalData as $item) {
                $data = DB::table('el_registration')->where('sn', $item->sn)->get();
                if (count($data) > 1) { //如果根据SN匹配到多条数据，则不处理
                    $log->addInfo("该sn匹配到多条数据：" . $item->sn);
                }

                if (count($data) == 1) {
                    foreach ($data as $value) {
                        $order = DB::table('el_order')->where('order_no', $item->order_number)->first();
                        if ($order) {
                            if (DB::table('el_registration')->where('id', $value->id)->whereNull('order_id')->update(['order_id' => $order->id])) {
                                $log->addInfo("sn等于 " . $item->sn . " 的数据处理完成");
                            }
                        }
                    }
                }
            }
        } else {
            $message = '全部处理完成';
        }

        $interval = 5;
        $url_bit = route('admin.data.handleFuntasyRegistrationData', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    public function handleReissueMobilePoint()
    {
        $page = 1;
        $limit = request('limit') ? request('limit') : 100;
        $message = '';

        $users = DB::table('el_user')
            //->leftJoin('el_point', 'el_user.id', '=', 'el_point.user_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('el_point')
                    ->whereRaw('el_point.user_id = el_user.id')
                    ->where('el_point.action', 'verify_mobile');
            })->paginate($limit);


        if (count($users) > 0) {
            foreach ($users as $user) {

                if (settings('point_enabled')) {

                    if ($this->point->getRecordByAction($user->id, 'verify_mobile')) {

                        $value = config('uto.point_rule.verify_mobile.base');

                        $this->point->create(['user_id' => $user->id, 'action' =>
                            'verify_mobile', 'note' => '验证手机号码', 'item_type' => User::class,
                            'item_id' => $user->id
                            , 'created_at' => $user->created_at
                            , 'updated_at' => $user->updated_at
                            , 'value' => $value]);

                        event('point.change', $user->id);
                    }
                }
            }
        } else {
            $message = '全部处理完成';
        }

        $interval = 5;
        $url_bit = route('admin.data.handleReissueMobilePoint', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    public function handleFuntasyRegistrationChannelData()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 100;
        $message = '';

        $registrations = Registration::where('status', 3)->whereNull('channel')->where('used_at', '>', '2017-04-09')->paginate($limit);

        if (count($registrations) > 0) {
            foreach ($registrations as $item) {
                $erp = ErpDeliveriesData::where('sn', $item->sn)->first();

                if (!$erp) {
                    continue;
                }

                if (str_contains($erp->customer_channel_name, '亚马逊')) {
                    $item->channel = 'osprey_amazon';
                    $item->save();
                } else
                    if (str_contains($erp->customer_channel_name, '天猫')) {
                        $item->channel = 'osprey_tmall';
                        $item->save();
                    } else
                        if (str_contains($erp->customer_channel_name, '淘宝')) {
                            $item->channel = 'osprey_taobao';
                            $item->save();
                        } else
                            if (str_contains($erp->customer_channel_name, '官网')) {
                                $item->channel = 'osprey';
                                $item->save();
                            } else
                                if (str_contains($erp->customer_channel_name, '公司')) {
                                    $item->channel = 'offline_store';
                                    $item->save();
                                } else {
                                    $item->channel = 'other';
                                    $item->save();
                                }
            }
        } else {
            $message = '全部处理完成';
        }

        $interval = 5;
        $url_bit = route('admin.data.handleFuntasyRegistrationChannelData', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    /**
     *处理收货后积分未生效任务
     */
    public function handleReceivePoint()
    {
        $page = 1;
        $limit = request('limit') ? request('limit') : 20;
        $message = '';

        $delay = app('system_setting')->getSetting('order_can_refund_day') ? app('system_setting')->getSetting('order_can_refund_day') : 7;
        //oip = OrderItemPoint::where('status', 0)->whereRaw('(DATEDIFF(now(),created_at) >= ' . $delay . ')')->get();

        $orderTable = 'el_order';
        $orderItemTable = 'el_order_item';
        $pointTable = 'el_point';

        $points = Point::join($orderItemTable, $orderItemTable . '.id', '=', $pointTable . '.item_id')
            ->join($orderTable, $orderTable . '.id', '=', $orderItemTable . '.order_id')
            ->where($pointTable . '.status', 0)
            ->where('el_point.action', 'order_item')
            ->whereNotNull($orderTable . '.accept_time')
            ->whereRaw('(DATEDIFF(now(),el_order.accept_time) >= ' . $delay . ')')
            ->take($limit)->select('el_point.*')->get();

        if (count($points) > 0) {
            foreach ($points as $item) {
                $point = Point::find($item->id);
                if ($order = $point->getOrder() AND $order->accept_time AND strtotime($order->accept_time) < Carbon::now()->addDay(-$delay)->timestamp) {
                    $point->update(['status' => 1]);
                    event('point.change', $point->user_id);
                }
            }
        } else {
            $message = '全部处理完成';
        }

        $interval = 3;
        $url_bit = route('admin.data.handleReceivePoint', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    public function handleHomeCache()
    {
        Cache::forget('mobile-home-menu');
        Cache::forget('mobile-home-index');
        Cache::forget('mobile-home-modules-data');
        Cache::forget('mini-home-modules-data');
        Cache::forget('mini-program-category-list');
        return '操作成功';
    }

    public function runPayment(PaymentService $paymentService)
    {
        if (request()->isMethod('GET')) {
            return view('store-backend::data.payment');
        }
        $event = json_decode(request('payment'), true);

        if ($event['type'] == 'charge.succeeded') {

            $charge = $event['data']['object'];

            if ($charge['paid']) {  //如果支付成功

                //$this->paymentService->paySuccess($charge);
                $paymentService->paySuccess($charge);
            }
            return '支付成功';
        }
        return '支付失败';
    }


    public function handlePCHeadMenuCache()
    {
        if (settings('pc_store_domain_url')) {
            return redirect(settings('pc_store_domain_url') . '/Cache/handlePCHeadMenuCache');
        }
        Cache::forget('mobile-home-menu');
        return '操作成功';
    }

    /**
     * 处理分类，新增level和path字段值
     */
    public function handleCategory()
    {
        $category = $this->categoryRepository->getSortCategory(0);
        return $this->updateCategoryTree($category);
    }

    private function updateCategoryTree($categories, $pid = 0, $level = 0, $dep = '/')
    {
        $result = array();
        foreach ($categories as $v) {
            if ($v->parent_id == $pid) {
                $path = $dep . $v->id . '/';
                $v->level = $level + 1;
                $v->path = $path;
                $v->save();
                self::updateCategoryTree($categories, $v->id, $level + 1, $path);
            }
        }
        return $result;
    }

    public function clearGoodsCache()
    {
        Cache::forget('single_discount_cache');
        Cache::forget('goods_attribute_cache');
        Cache::forget('product_single_discount');
        Cache::forget('goods_discount_cache');
        Cache::forget('o2o_goods_discount_cache');
        Cache::forget('limit_goods_recommendations');
        return '操作成功';
    }

    /**
     * 获取用户UnionID
     * @return mixed
     */
    public function getWxUnionID()
    {
        $page = 1;
        $limit = request('limit') ? request('limit') : 20;
        $message = '';

        $user = \ElementVip\Member\Backend\Models\User::whereNull('union_id')->paginate($limit);
        if (count($user) > 0) {
            foreach ($user as $item) {
                if ($bind = $item->bind AND $bind->type == 'wechat') {
                    $wxInfo = wechat_channel()->getUserInfo($bind->open_id);
                    \Log::info('data handle:' . json_encode($wxInfo));
                    if (!$wxInfo) {
                        $wxInfo = wechat_channel()->getUserInfo($bind->open_id, true);
                        \Log::info('data handle2:' . json_encode($wxInfo));
                    }
                    if ($wxInfo AND !isset($wxInfo->errcode) AND isset($wxInfo->unionid)) {
                        if (!User::where('union_id', $wxInfo->unionid)->first()) {
                            $item->union_id = $wxInfo->unionid;
                            $item->save();
                        }
                    }
                }
            }

        } else {
            $message = '全部处理完成';
        }

        $interval = 10;
        $url_bit = route('admin.data.getWxUnionID', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));

    }


    /**
     * 改造了商品评论表，需要把老数据处理一下
     * @return mixed
     */
    public function handleGoodsComment()
    {
        $page = 1;
        $limit = request('limit') ? request('limit') : 20;
        $message = '';

        $data = Comment::where('goods_id', 0)->paginate($limit);
        if (count($data) > 0) {
            foreach ($data as $item) {
                $item->goods_id = $item->item_meta['detail_id'];
                $item->save();
            }
        } else {
            $message = '全部处理完成';
        }

        $interval = 10;
        $url_bit = route('admin.data.handleGoodsComment', ['page' => $page, 'limit' => $limit]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));

    }

    public function createCustomerMiniCode()
    {
        $save_path = 'public/customer/' . rand(999, 9999) . '.jpg';
        $page = 'pages/content/main';
        $mini = $this->miniGetWxaCode->createMiniQrcode($page, 800, $save_path, 'B','2');
        if ($mini) {
            dd('success');
        }
        dd('failed');
    }
}
