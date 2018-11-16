<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use ElementVip\Component\User\Models\Role;
use iBrand\EC\Open\Backend\Store\Model\Category;
use iBrand\EC\Open\Backend\Store\Model\ElDiscount;
use iBrand\EC\Open\Backend\Store\Model\ElDiscountAction;
use iBrand\EC\Open\Backend\Store\Model\ElDiscountRule;
use iBrand\EC\Open\Backend\Store\Model\Goods;
use iBrand\EC\Open\Backend\Store\Model\ElDiscountCoupon;
use iBrand\EC\Open\Backend\Store\Service\DiscountService;
use Illuminate\Http\Request;

use ElementVip\Component\User\Models\Group;
use iBrand\EC\Open\Backend\Store\Repositories\CategoryRepository;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsRepository;
use iBrand\EC\Open\Backend\Store\Repositories\DiscountRepository;
use iBrand\EC\Open\Backend\Store\Repositories\DiscountCouponRepository;
use iBrand\EC\Open\Backend\Store\Facades\ExcelExportsService;
use iBrand\EC\Open\Backend\Store\Service\GoodsService;
use iBrand\EC\Open\Backend\Store\Repositories\OrderAdjustmentRepository;

use Response;

class PromotionsController extends \ElementVip\Backend\Http\Controllers\Controller
{
    protected $categoryRepository;
    protected $goodsRepository;
    protected $discountRepository;
    protected $discountCouponRepository;
    protected $discountService;
    protected $goodsService;
    protected $orderAdjustmentRepository;

    public function __construct(CategoryRepository $categoryRepository,
                                GoodsRepository $goodsRepository,
                                DiscountRepository $discountRepository,
                                DiscountCouponRepository $discountCouponRepository,
                                DiscountService $discountService,
                                GoodsService $goodsService,
                                OrderAdjustmentRepository $orderAdjustmentRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->goodsRepository = $goodsRepository;
        $this->discountRepository = $discountRepository;
        $this->discountCouponRepository = $discountCouponRepository;
        $this->discountService = $discountService;
        $this->goodsService = $goodsService;
        $this->orderAdjustmentRepository = $orderAdjustmentRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $where = [];
        if (request('coupon_based') != '') {
            $where['coupon_based'] = request('coupon_based');
        }
        if (request('status') != '') {
            $where['status'] = request('status');
        }
        if (request('title') != '') {
//            $where['title']=request('title');
            $where['title'] = ['like', '%' . request('title') . '%'];
        }
        $discount = $this->discountRepository->getCouponList($where);
//        $discount = ElDiscount::where($where)->orderBy('created_at','desc')->paginate(15);

        return view('store-backend::promotion.index', compact('discount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $groups = Group::all();

        $roles = Role::all();

        return view('store-backend::promotion.create', compact('groups', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $base = $request->input('base');
        $rules = $request->input('rules');
        $action = $request->input('action');
        $base['per_usage_limit'] = !empty($base['per_usage_limit']) ? $base['per_usage_limit'] : $base['usage_limit'];
        if ($request->input('id')) { //修改

            $discount = ElDiscount::find($request->input('id'));
            $discount->fill($base);
            $discount->save();

            //action
            $actionData = ElDiscountAction::find($request->input('action_id'));
            $actionData->fill($action);
            $actionData->save();

            if ($pointAction = ElDiscountAction::find($request->input('point_action_id'))) {
                if(request('point-action')['configuration']){
                    $pointAction->fill(request('point-action'));
                    $pointAction->save();
                }else{
                    $pointAction->delete();
                }

            }elseif(request('point-action')['configuration']){
                $addPointAction = request('point-action');
                $addPointAction['discount_id'] = $discount->id;
                $pointAction = ElDiscountAction::create($addPointAction);
            }

            //delete rules
            $discount->discountRules()->delete();

        } else {
            $discount = ElDiscount::create($base);
            //action
            $action['discount_id'] = $discount->id;
            ElDiscountAction::create($action);

            if(request('point-action')['configuration']){
                $addPointAction = request('point-action');
                $addPointAction['discount_id'] = $discount->id;
                $pointAction = ElDiscountAction::create($addPointAction);
            }
        }

        if ($discount && !empty($rules)) {
            //rules
            foreach ($rules as $key => $val) {
                $rulesData = [];
                $rulesData['discount_id'] = $discount->id;
                $rulesData['type'] = $val['type'];
                $rulesData['configuration'] = $val['value'];

                ElDiscountRule::create($rulesData);
            }

        }


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $discount = ElDiscount::find($id);
        $category = $this->categoryRepository->getLevelCategory(0);
        $roles = Role::all();
        return view('store-backend::promotion.edit', compact('discount', 'category', 'roles'));
    }


    public function getCategory()
    {
        $category = $this->categoryRepository->getLevelCategory(0);
        $num = request('num');
        return view('store-backend::promotion.includes.rule_category', compact('category', 'num'));
    }

    public function getSpu(Request $request)
    {     
        $url = 'admin.promotion.getSpuData';
        $action = $request->input('action');

        return view('store-backend::promotion.includes.modal.getSpu', compact('goods', 'url', 'action'));
    }

    public function getSpuData(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $action = $request->input('action');

        $where = [];
        $where_ = [];

        $where['is_del'] = ['=', 0];

        if (!empty(request('value')) AND request('field') !== 'sku' AND request('field') !== 'category') {
            $where[request('field')] = ['like', '%' . request('value') . '%'];
        }

        if (!empty(request('store_begin')) && !empty(request('store_end'))) {
            $where['store_nums'] = ['>=', request('store_begin')];
            $where_['store_nums'] = ['<=', request('store_end')];
        }

        if (!empty(request('store_begin'))) {
            $where_['store_nums'] = ['>=', request('store_begin')];
        }

        if (!empty(request('store_end'))) {
            $where_['store_nums'] = ['<=', request('store_end')];
        }

        if (!empty(request('price_begin')) && !empty(request('price_end'))) {
            $where[request('price')] = ['>=', request('price_begin')];
            $where_[request('price')] = ['<=', request('price_end')];
        }

        if (!empty(request('price_begin'))) {
            $where_[request('price')] = ['>=', request('price_begin')];
        }

        if (!empty(request('price_end'))) {
            $where_[request('price')] = ['<=', request('price_end')];
        }

        $goods_ids = [];
        if (request('field') == 'sku' && !empty(request('value'))) {
            $goods_ids = $this->goodsService->skuGetGoodsIds(request('value'));
        }
        if (request('field') == 'category' && !empty(request('value'))) {
            $goods_ids = $this->goodsService->categoryGetGoodsIds(request('value'));
        }

        if ($action == 'view' OR $action == 'view_exclude') {
            $goods_ids = array_merge($goods_ids, $ids);
        }

        $goods = $this->goodsRepository->getGoodsPaginated($where, $where_, $goods_ids, 15)->toArray();
        $goods['ids'] = $ids;

        return $this->ajaxJson(true, $goods);

//        return view('store-backend::promotion.includes.getSpuPost', compact('goods', 'ids'));
    }

    public function showCoupons($id)
    {
        $coupons = ElDiscountCoupon::where('discount_id', $id)->orderBy('created_at', 'desc')->paginate(15);
        return view('store-backend::promotion.show', compact('coupons'));
    }

    /**
     * 优惠券使用记录列表
     */
    public function useRecord()
    {
        $time = [];
        $where = [];
        if (!empty(request('value'))) {
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

        if (request('excel') == 1) {
            $coupons = $this->discountCouponRepository->getCouponsHistoryPaginated($where, 0, $time);
            $title = ['生成时间', '优惠券名', '优惠券码', '订单编号', '订单总金额', '订单状态', '用户名', '摘要'];
            return $this->discountService->searchAllCouponsHistoryExcel('Coupons_', $coupons, $title);

        } else {
            $coupons = $this->discountCouponRepository->getCouponsHistoryPaginated($where, 50, $time);
            return view('store-backend::promotion.use_record', compact('coupons'));

        }
    }

    /**
     * 优惠券使用记录列表
     */
    public function saleUseRecord()
    {
        $time = [];
        $where = [];
        if (!empty(request('order_no'))) {
            $where['order_no'] = ['like', '%' . request('order_no') . '%'];
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

        if (request('excel') == 1) {

            $coupons = $this->orderAdjustmentRepository->getOrderAdjustmentHistory($where, 0, $time);
            $title = ['促销描述','订单编号', '订单总金额', '订单状态', '用户名','使用时间'];
            return $this->discountService->searchAllOrderAdjustmentHistoryExcel('Discount_', $coupons, $title);

        } else {
            $coupons = $this->orderAdjustmentRepository->getOrderAdjustmentHistory($where, 50, $time);
            return view('store-backend::promotion.sale_use_record', compact('coupons'));

        }
    }

// 保存excel到服务器
    public function excelExport()
    {
        $date = !empty(request()->input('date')) ? request()->input('date') : [];
        $title = !empty(request()->input('title')) ? request()->input('title') : [];
        $name=!empty(request('name')) ? request('name') : 'Coupons_';
        return ExcelExportsService::createExcelExport($name, $date, $title);

    }

// 导出下载excel文件
    public function download($url)
    {
        return Response::download(storage_path() . "/exports/" . $url);
    }
}
