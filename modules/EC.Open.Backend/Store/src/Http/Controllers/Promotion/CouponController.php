<?php
namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers\Promotion;

use Carbon\Carbon;
use iBrand\Backend\Http\Controllers\Controller;
use GuoJiangClub\EC\Open\Backend\Store\Model\ElDiscount;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\DiscountRepository;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CategoryRepository;
use GuoJiangClub\EC\Open\Backend\Store\Service\DiscountService;
use DB;
use Validator;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\OrderAdjustmentRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\DiscountCouponRepository;
use DNS2D;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class CouponController extends Controller
{
    protected $discountRepository;
    protected $categoryRepository;
    protected $discountService;
    protected $orderAdjustmentRepository;
    protected $couponRepository;
    protected $cache;

    public function __construct(DiscountRepository $discountRepository,
                                CategoryRepository $categoryRepository,
                                DiscountService $discountService,
                                OrderAdjustmentRepository $orderAdjustmentRepository,
                                DiscountCouponRepository $discountCouponRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->categoryRepository = $categoryRepository;
        $this->discountService = $discountService;
        $this->orderAdjustmentRepository = $orderAdjustmentRepository;
        $this->couponRepository = $discountCouponRepository;
        $this->cache = cache();
    }

    public function index()
    {
        $condition = $this->getCondition();
        $where = $condition[0];
        $orWhere = $condition[1];

        $coupons = $this->discountRepository->getDiscountList($where, $orWhere);

        return LaravelAdmin::content(function (Content $content) use ($coupons) {

            $content->header('优惠券列表');

            $content->breadcrumb(
                ['text' => '优惠券管理', 'url' => 'store/promotion/coupon', 'no-pjax' => 1],
                ['text' => '优惠券列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '优惠券管理']

            );

            $content->body(view('store-backend::promotion.coupon.index', compact('coupons')));
        });
    }

    /**
     * 获取筛选条件
     * @return array
     */
    private function getCondition()
    {
        $where['coupon_based'] = 1;
        $orWhere = [];
        $status = request('status');
        if ($status == 'nstart') {
            $where['status'] = 1;
            $where['starts_at'] = ['>', Carbon::now()];
        }

        if ($status == 'ing') {
            $where['status'] = 1;
            $where['starts_at'] = ['<=', Carbon::now()];
            $where['ends_at'] = ['>', Carbon::now()];
        }

        if ($status == 'end') {
            $where['ends_at'] = ['<', Carbon::now()];

            $orWhere['coupon_based'] = 1;
            $orWhere['status'] = 0;
        }

        if (request('title') != '') {
            $where['title'] = ['like', '%' . request('title') . '%'];
        }

        return [$where, $orWhere];
    }

    public function create()
    {
        $category = $this->categoryRepository->getLevelCategory(0);
        $discount = new ElDiscount();

        return LaravelAdmin::content(function (Content $content) use ($category, $discount) {

            $content->header('新增优惠券');

            $content->breadcrumb(
                ['text' => '优惠券管理', 'url' => 'store/promotion/coupon', 'no-pjax' => 1],
                ['text' => '新增优惠券', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '优惠券管理']

            );

            $content->body(view('store-backend::promotion.coupon.create', compact('discount', 'category')));
        });

    }

    public function edit($id)
    {
        $discount = ElDiscount::find($id);
        $category = $this->categoryRepository->getLevelCategory(0);

        return LaravelAdmin::content(function (Content $content) use ($discount, $category) {

            $content->header('编辑优惠券');

            $content->breadcrumb(
                ['text' => '优惠券管理', 'url' => 'store/promotion/coupon', 'no-pjax' => 1],
                ['text' => '编辑优惠券', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '优惠券管理']

            );

            $content->body(view('store-backend::promotion.coupon.edit', compact('discount', 'category')));
        });
    }

    public function store(Request $request)
    {
        $base = $request->input('base');
        $rules = $request->input('rules');
        $action = $request->input('action');
        $point_action = $request->input('point-action');

        if (!$base['usestart_at']) unset($base['usestart_at']);


        $validator = $this->validationForm();
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return $this->ajaxJson(false, [], 404, $show_warning);
        }

        if (!$action['configuration'] AND !$point_action['configuration']) {
            return $this->ajaxJson(false, [], 404, '请至少设置一种优惠动作');
        }

        try {
            DB::beginTransaction();
            if (!$discount = $this->discountService->saveData($base, $action, $rules, 1)) {
                return $this->ajaxJson(false, [], 404, '请至少设置一种规则');
            }

            DB::commit();

            if ($this->cache->has('goods_discount_cache')) {
                $this->cache->forget('goods_discount_cache');
            }

            return $this->ajaxJson(true, [], 0, '');

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);
            return $this->ajaxJson(false, [], 404, '保存失败');
        }

    }

    protected function validationForm()
    {
        $rules = array(
            'base.title' => 'required',
            'base.code' => 'required',
            'base.usage_limit' => 'required | integer',
            'base.starts_at' => 'required | date',
            'base.ends_at' => 'required | date | after:base.starts_at',
        );
        $message = array(
            "required" => ":attribute 不能为空",
            "base.ends_at.after" => ':attribute 不能早于领取开始时间',
            "base.useend_at.after" => ':attribute 不能早于领取截止时间',
            "integer" => ':attribute 必须是整数'
        );

        $attributes = array(
            "base.title" => '优惠券名称',
            "base.code" => '兑换码',
            "base.usage_limit" => '发放总量',
            "base.starts_at" => '开始时间',
            "base.ends_at" => '领取截止时间',
            "base.useend_at" => '使用截止时间'
        );

        $validator = Validator::make(
            request()->all(),
            $rules,
            $message,
            $attributes
        );

        $validator->sometimes('base.useend_at', 'required|date|after:base.ends_at', function ($input) {
            return $input->base['useend_at'] < $input->base['ends_at'];
        });

        return $validator;
    }


    /**
     * 使用记录
     * @return mixed
     */
    public function useRecord()
    {
        $condition = $this->usedCondition();
        $where = $condition[1];
        $time = $condition[0];
        $id = request('id');

        $coupons = $this->couponRepository->getCouponsHistoryPaginated($where, 20, $time);

        return LaravelAdmin::content(function (Content $content) use ($coupons, $id) {

            $content->header('查看使用记录');

            $content->breadcrumb(
                ['text' => '优惠券管理', 'url' => 'store/promotion/coupon', 'no-pjax' => 1],
                ['text' => '查看使用记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '优惠券管理']

            );

            $content->body(view('store-backend::promotion.coupon.use_record', compact('coupons', 'id')));
        });
    }

    /**
     * 优惠券使用筛选条件
     * @return array
     */
    private function usedCondition()
    {
        $time = [];
        $where = [];

        $where['user_id'] = ['>', 0];

        if ($id = request('id')) {
            $where['discount_id'] = $id;
        }

        if (!empty(request('value'))) {
            $where[request('field')] = ['like', '%' . request('value') . '%'];
        }


        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['used_at'] = ['<=', request('etime')];
            $time['used_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['used_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['used_at'] = ['>=', request('stime')];
        }

        return [$time, $where];
    }

    /**
     * 领取记录
     * @param $id
     * @return mixed
     */
    public function showCoupons()
    {
        $condition = $this->getCouponCondition();
        $where = $condition[1];
        $time = $condition[0];
        $id = request('id');

        $coupons = $this->couponRepository->getCouponsPaginated($where, 15, $time);

        return LaravelAdmin::content(function (Content $content) use ($coupons, $id) {

            $content->header('查看领取记录');

            $content->breadcrumb(
                ['text' => '优惠券管理', 'url' => 'store/promotion/coupon', 'no-pjax' => 1],
                ['text' => '查看领取记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '优惠券管理']

            );

            $content->body(view('store-backend::promotion.coupon.show', compact('coupons', 'id')));
        });
    }

    private function getCouponCondition()
    {
        $time = [];
        $where = [];

        if ($id = request('id')) {
            $where['discount_id'] = $id;
        }

        if (!empty(request('value'))) {
            $where['mobile'] = ['like', '%' . request('value') . '%'];
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

        return [$time, $where];
    }
    
    
    /**
     * 导出使用记录
     * @return mixed
     */
    public function getUsedExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 20;
        $type = request('type');

        $condition = $this->usedCondition();
        $where = $condition[1];
        $time = $condition[0];

        $coupons = $this->couponRepository->getCouponsHistoryPaginated($where, $limit, $time);
        $lastPage = $coupons->lastPage();
        $coupons = $this->discountService->searchAllCouponsHistoryExcel($coupons);


        if ($page == 1) {
            session(['export_coupon_used_cache' => generate_export_cache_name('export_coupon_used_cache_')]);
        }
        $cacheName = session('export_coupon_used_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $coupons), 300);
        } else {
            $this->cache->put($cacheName, $coupons, 300);
        }

        if ($page == $lastPage) {
            $title = ['使用时间', '优惠券名', '优惠券码', '订单编号', '订单总金额', '订单状态', '用户名', '摘要'];
            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'coupon_used_data_']);
        } else {
            $url_bit = route('admin.promotion.coupon.getUsedExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));
            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
        }
    }


    /**
     * 导出领取记录
     * @return mixed
     */
    public function getCouponsExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 20;
        $type = request('type');

        $condition = $this->getCouponCondition();
        $where = $condition[1];
        $time = $condition[0];

        $coupons = $this->couponRepository->getCouponsPaginated($where, $limit, $time);
        $lastPage = $coupons->lastPage();
        $coupons = $this->discountService->couponsGetDataExcel($coupons);


        if ($page == 1) {
            session(['export_coupon_get_cache' => generate_export_cache_name('export_coupon_get_cache_')]);
        }
        $cacheName = session('export_coupon_get_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $coupons), 300);
        } else {
            $this->cache->put($cacheName, $coupons, 300);
        }

        if ($page == $lastPage) {
            $title = ['领取时间', '优惠码', '用户', '是否使用', '使用时间'];
            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'coupon_get_data_']);
        } else {
            $url_bit = route('admin.promotion.coupon.getCouponsExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));
            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
        }
    }
}