<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/16
 * Time: 14:56
 */
namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Model\Registrations;
use Illuminate\Support\Facades\DB;
use iBrand\EC\Open\Backend\Store\Repositories\OrderItemRepository;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use iBrand\EC\Open\Backend\Store\Repositories\RegistrationsRepository;
use Carbon\Carbon;

class DataProcessController extends Controller
{

    protected $orderItemRepository;
    protected $registrationsRepository;

    public function __construct(OrderItemRepository $orderItemRepository, RegistrationsRepository $registrationsRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->registrationsRepository = $registrationsRepository;
    }

    public function orderItem()
    {
        $page = request('page');
        $limit = request('limit');

        $oldOrderItemArray = DB::table('order_goods')->orderBy('id', 'asc')->paginate($limit);
        /*DB::select('select id,order_id,goods_id,goods_name,img,product_id,goods_price,goods_nums,goods_array,created_at,updated_at,deleted_at from order_goods ')->skip($page*($limit-1))->take($limit)->get();*/
        // dd($oldOrderItemArray);
        foreach ($oldOrderItemArray as $key => $item) {
            $item_meta = [];
            $new_order_item = [];
            $goods_array = json_decode($item->goods_array);
            $new_order_item['id'] = $item->id;
            $new_order_item['order_id'] = $item->order_id;

            if ($item->product_id > 0) {
                $new_order_item['item_id'] = $item->product_id;
                $new_order_item['type'] = 'iBrand\Component\Product\Models\Product';
            } else {
                $new_order_item['item_id'] = $item->goods_id;
                $new_order_item['type'] = 'iBrand\Component\Product\Models\Goods';
            }

            $new_order_item['item_name'] = $item->goods_name;
            $item_meta = array(
                'image' => $item->img,
                'spec_text' => isset($goods_array->value_str) ? $goods_array->value_str : '',
                'detail_id' => $item->goods_id
            );
            $new_order_item['item_meta'] = json_encode($item_meta);
            $new_order_item['quantity'] = $item->goods_nums;
            $new_order_item['unit_price'] = $item->goods_price * 100;
            $new_order_item['units_total'] = $item->goods_price * $item->goods_nums * 100;
            $coupon_list = DB::select('select c.* from orders b,coupons c,coupon_codes d where  b.coupon_item= d.coupon_code and d.coupon_id=c.id and b.id =' . $item->order_id, array());

            if (isset($coupon_list) && count($coupon_list)) {
                if ($coupon_list[0]->goods_id == 0) {
                    $countArray = DB::select('select count(*)  as count from order_goods where order_id=' . $item->order_id, array());
                } else {
                    $countArray = DB::select('select count(*)  as count from order_goods where order_id=' . $item->order_id . ' and goods_id in(' . $coupon_list[0]->goods_id . ')', array());

                }
                $adjustments_total = $coupon_list[0]->face_value / $countArray[0]->count;
            }
            $adjustments_total = isset($adjustments_total) ? $adjustments_total : 0;
            $new_order_item['adjustments_total'] = -1 * $adjustments_total * 100;

            $new_order_item['total'] = $new_order_item['units_total'] * 100 + $new_order_item['adjustments_total'] * 100;
//            $new_order_item['created_at'] = $item->created_at;
//            $new_order_item['updated_at'] = $item->updated_at;
            $new_order_item['deleted_at'] = $item->deleted_at;
            $this->orderItemRepository->create($new_order_item);
        }

        $message = '正在同步订单数据';
        $interval = 5;
        $url_bit = route('admin.data.orderItem', ['page' => $page + 1, 'limit' => $limit]);
        return view('store-backend::show_message', compact('message', 'url_bit', 'interval'));
    }

    public function note()
    {
        $sql = DB::select('select * from notes where user_id!=98489', array());
        foreach ($sql as $item) {
            $thread = Thread::create(
                [
                    'subject' => '系统消息',
                ]
            );
            Message::create(
                [
                    'thread_id' => $thread->id,
                    'user_id' => 1,
                    'body' => $item->notes,
                ]
            );
            Participant::create(
                [
                    'thread_id' => $thread->id,
                    'user_id' => $item->to_userid,
                    'last_read' => new Carbon,
                ]
            );

            $thread->addParticipant($item->to_userid);
        }


    }

    public function registration_list()
    {
        $registration_order = DB::select('select id,registration_id from orders where registration_id!=0', array());
        foreach ($registration_order as $item) {
            if ($registrations = Registrations::find($item->registration_id)) {
                $registrations->order_id = $item->id;
                $registrations->save();
            }
        }

    }
}