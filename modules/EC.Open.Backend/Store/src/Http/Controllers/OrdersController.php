<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use Carbon\Carbon;
use iBrand\Backend\Http\Controllers\Controller;
use GuoJiangClub\EC\Open\Backend\Store\Model\Shipping;
use GuoJiangClub\EC\Open\Backend\Store\Model\ShippingMethod;
use GuoJiangClub\EC\Open\Backend\Store\Model\Order;
use Illuminate\Http\Request;
use Response;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\OrderRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\OrderItemRepository;
use Excel;
use GuoJiangClub\EC\Open\Backend\Store\Facades\OrderService;
use DB;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class OrdersController extends Controller
{
    protected $orderRepository;
    protected $orderItemsRepository;
    protected $productRepository;
    protected $orderLogRepository;
    protected $cache;

    public function __construct(OrderRepository $orderRepository
        , OrderItemRepository $orderItemsRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemsRepository = $orderItemsRepository;
        $this->cache = cache();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = request('status');

        $users = null;
        $condition = $this->createConditions();
        $where = $condition[0];
        $time = $condition[1];
        $pay_time = $condition[2];

        $more_filter = request('more_filter');

        $freightCompany = ShippingMethod::all();
        $orders = $this->orderRepository->getExportOrdersData($where, 20, $time, $pay_time);

        return LaravelAdmin::content(function (Content $content) use ($orders, $view, $freightCompany, $more_filter, $users) {

            $content->header('订单列表');

            $content->breadcrumb(
                ['text' => '订单管理', 'url' => 'store/order?status=all', 'no-pjax' => 1],
                ['text' => '订单列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '订单列表']

            );

            $content->body(view('store-backend::orders.index', compact('orders', 'view', 'freightCompany', 'users')));
        });
    }

    protected function createConditions()
    {
        $time = [];
        $where = [];
        $pay_time = [];

        if (request('status') == 'all' OR !request('status')) {
            $where['status'] = ['>', 0];
        } else {
            $where['status'] = request('status');
        }

        if (!empty($pay_status = request('pay_status'))) {
            $where['pay_status'] = $pay_status == 'paid' ? 1 : 0;
        }

        if (!empty(request('order_status'))) {
            $where['status'] = request('order_status');
        }

        if (!empty(request('user_id'))) {
            $where['user_id'] = request('user_id');
        }

        if (!empty(request('value')) AND !empty(request('field'))) {
            $where[request('field')] = ['like', '%' . request('value') . '%'];
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }

        /*付款时间*/
        if (!empty(request('s_pay_time')) && !empty(request('e_pay_time'))) {
            $where['pay_time'] = ['<=', request('e_pay_time')];
            $pay_time['pay_time'] = ['>=', request('s_pay_time')];
        }

        if (!empty(request('e_pay_time'))) {
            $pay_time['pay_time'] = ['<=', request('e_pay_time')];
        }

        if (!empty(request('s_pay_time'))) {
            $pay_time['pay_time'] = ['>=', request('s_pay_time')];
        }


        return [$where, $time, $pay_time];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $order = $this->orderRepository->findOrThrowException($id);
        $order_deliver = $order->deliver;

        $adjustments = $this->orderRepository->getOrderAdjustments($order);
        $shipping = $this->orderRepository->getShippingMethod($order);
        $orderPoint = $this->orderRepository->getOrderPoints($order->items);
        $orderConsumePoint = $this->orderRepository->getOrderConsumePoint($order->id);


        return LaravelAdmin::content(function (Content $content) use ($order, $order_deliver, $shipping, $adjustments, $orderPoint, $orderConsumePoint) {

            $content->header('订单详情');

            $content->breadcrumb(
                ['text' => '订单管理', 'url' => 'store/order?status=all', 'no-pjax' => 1],
                ['text' => '订单详情', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '订单列表']

            );

            $content->body(view('store-backend::orders.show', compact('order', 'order_deliver', 'orderGoods', 'shipping', 'adjustments', 'orderPoint', 'orderConsumePoint')));
        });

    }


    /**
     * 单个订单发货modal
     * @param  $id
     *
     * @return mixed
     */

    public function ordersDeliver($id)
    {
        $order_id = $id;
        $freightCompany = ShippingMethod::all();
        $redirect_url = request('redirect_url');

        return view('store-backend::orders.includes.order_deliver', compact('order_id', 'freightCompany', 'redirect_url'));
    }


    /**
     * @param Request $request
     * @param         $id
     *
     * @return mixed
     */

    public function ordersDeliverEdit(Request $request, $id)
    {
        $freightCompany = ShippingMethod::all();
        $shipping = Shipping::where(['order_id' => $id])->first();
        $order_id = $id;

        return view('store-backend::orders.includes.order_deliver_edit', compact('order_id', 'freightCompany', 'shipping'));
    }

    /**
     * 后台发货动作
     *
     * @param Request $request
     * @param         $id
     */
    public function deliver(Request $request)
    {
        $data = $request->except('shipping_id');

        if (!isset($data['method_id']) OR !$data['method_id'] OR !$data['tracking']) {
            return $this->ajaxJson(false, [], 403, '请完善发货信息');
        }

        $orderIds = explode(',', $data['order_id']);
        if (empty($orderIds)) {
            return $this->ajaxJson(false, [], 403, '订单不存在');
        }

        try {
            DB::beginTransaction();
            if (request('shipping_id')) {
                foreach ($orderIds as $orderId) {
                    $data['order_id'] = $orderId;
                    Shipping::where(['id' => request('shipping_id')])->update($data);
                }
            } else {
                $this->delivering($orderIds, $data);
            }
            DB::commit();
            return $this->ajaxJson();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }

    /**
     * 发货操作
     *
     * @param $orderIds array 订单id数组
     *
     * @return bool
     */
    public function delivering($orderIds, $shippingMessage)
    {
        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                continue;
            }

            if ($order->distribution_status == 1) {
                continue;
            }

            $shipping = Shipping::create($shippingMessage);
            $order->distribution_status = 1;
            $order->status = Order::STATUS_DELIVERED;
            $order->send_time = $shippingMessage['delivery_time'];

            $order->save();
        }

        return true;
    }


    /**
     * 批量导入订单发货modal
     *
     * @return mixed
     */
    public function ordersImport()
    {
        return view('store-backend::orders.includes.order_import');
    }

    /**
     * 批量导入订单发货动作
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function importOrderSend(Request $request)
    {
        $filename = 'public' . $request['upload_excel'];
        $error_list = [];
        try {
            DB::beginTransaction();
            Excel::load($filename, function ($reader) use (&$error_list) {
                $reader = $reader->getSheet(0);
                //获取表中的数据
                $results = $reader->toArray();
                foreach ($results as $key => $value) {
                    if ($key <= 0) {
                        continue;
                    }

                    $order_no = trim($value[0]);
                    $ship_name = trim($value[2]);
                    $number = trim($value[1]);
                    if (!$order_no OR !$ship_name OR !$number) {
                        $error_list[] = '遇到空白行数据，中断导入';
                        break;
                    }

                    $order = $this->orderRepository->findWhere(['order_no' => $order_no])->first();
                    $freightCompany = ShippingMethod::where(['name' => $ship_name])->first();
                    if (!$order) {
                        $error_list[] = '订单 ' . $order_no . ' 不存在；';
                        continue;
                    }

                    if (!$freightCompany) {
                        $error_list[] = '订单 ' . $order_no . ' 对应的快递公司‘' . $ship_name . '’不存在；';
                        continue;
                    }

                    $shippingMessage = [
                        'method_id' => $freightCompany->id,
                        'tracking' => $number,
                        'delivery_time' => date('y-m-d H:i:s', time()),
                    ];
                    $this->delivering([$order->id], $shippingMessage);
                }
            });
            DB::commit();

            return response()->json(['status' => true
                , 'error_code' => 0
                , 'data' => ['error_list' => $error_list],
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '操作失败');
        }
    }


    public function close($id)
    {
        $order = Order::find($id);

        $order->status = Order::STATUS_CANCEL;
        $order->completion_time = Carbon::now();
        $order->save();

        return $this->ajaxJson(true, [], 200, '订单取消成功');
    }


    /**
     * 获取需要导出的数据
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 50;
        $type = request('type');

        $condition = $this->createConditions();
        $where = $condition[0];
        $time = $condition[1];
        $pay_time = $condition[2];

        $orders = $this->orderRepository->getExportOrdersData($where, 50, $time, $pay_time);
        $lastPage = $orders->lastPage();

        foreach ($orders as $item) {
            $item->pay_type = isset($item->payment->channel) ? $item->payment->channel : '';
            $item->channel_no = isset($item->payment->channel_no) ? $item->payment->channel_no : '';
        }

        $orderExcelData = OrderService::formatToExcelData($orders);

        if ($page == 1) {
            session(['export_orders_cache' => generate_export_cache_name('export_orders_cache_')]);
        }
        $cacheName = session('export_orders_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $orderExcelData), 300);
        } else {
            $this->cache->put($cacheName, $orderExcelData, 300);
        }

        if ($page == $lastPage) {
            $title = ['订单编号', '所属品牌', '下单会员', '收货人', '省', '市', '区', '收货地址', '联系电话', '邮箱地址', '商品名称', 'sku', '规格', '吊牌价', '售价', '购买数量', '商品应付金额', '优惠活动名称', '优惠抵扣金额', '积分抵扣金额', '订单状态', '支付状态', '支付平台', '支付流水号', '发货状态', '订单应付金额', '下单时间', '付款时间', '用户留言'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'orders_data_']);
        } else {
            $url_bit = route('admin.orders.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));

            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
        }
    }

    /**
     * 修改收货地址
     */
    public function editAddress($order_id)
    {
        $order = Order::find($order_id);
        $address = explode(' ', $order->address_name);

        return view('store-backend::orders.includes.modal_address', compact('order', 'address'));
    }

    public function postAddress()
    {
        $order = Order::find(request('order_id'));
        $order->accept_name = request('accept_name');
        $order->mobile = request('mobile');
        $order->address = request('address');
        $order->address_name = request('province') . ' ' . request('city') . ' ' . request('district');
        $order->save();

        return $this->ajaxJson();
    }
}
