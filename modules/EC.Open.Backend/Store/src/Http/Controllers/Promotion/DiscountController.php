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
use Cache;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class DiscountController extends Controller
{
	protected $discountRepository;
	protected $categoryRepository;
	protected $discountService;
	protected $orderAdjustmentRepository;
	protected $cache;

	public function __construct(DiscountRepository $discountRepository,
	                            CategoryRepository $categoryRepository,
	                            DiscountService $discountService,
	                            OrderAdjustmentRepository $orderAdjustmentRepository)
	{
		$this->discountRepository        = $discountRepository;
		$this->categoryRepository        = $categoryRepository;
		$this->discountService           = $discountService;
		$this->orderAdjustmentRepository = $orderAdjustmentRepository;
		$this->cache                     = cache();
	}

	public function index()
	{

		$condition = $this->condition();
		$where     = $condition[0];
		$orWhere   = $condition[1];

		$discount = $this->discountRepository->getDiscountList($where, $orWhere);

		return LaravelAdmin::content(function (Content $content) use ($discount) {

			$content->header('促销活动列表');

			$content->breadcrumb(
				['text' => '促销活动管理', 'url' => 'store/promotion/discount', 'no-pjax' => 1],
				['text' => '促销活动列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '促销活动管理']

			);

			$content->body(view('store-backend::promotion.discount.index', compact('discount')));
		});
	}

	private function condition()
	{
		$where['coupon_based'] = 0;
		$orWhere               = [];
		$status                = request('status');
		if ($status == 'nstart') {
			$where['status']    = 1;
			$where['starts_at'] = ['>', Carbon::now()];
		}

		if ($status == 'ing') {
			$where['status']    = 1;
			$where['starts_at'] = ['<=', Carbon::now()];
			$where['ends_at']   = ['>', Carbon::now()];
		}

		if ($status == 'end') {
			$where['ends_at'] = ['<', Carbon::now()];

			$orWhere['coupon_based'] = 0;
			$orWhere['status']       = 0;
		}

		if (request('title') != '') {
			$where['title'] = ['like', '%' . request('title') . '%'];
		}

		return [$where, $orWhere];
	}

	public function create()
	{
		$category = $this->categoryRepository->getLevelCategory(0);

		return LaravelAdmin::content(function (Content $content) use ($category) {

			$content->header('新建促销活动');

			$content->breadcrumb(
				['text' => '促销活动管理', 'url' => 'store/promotion/discount', 'no-pjax' => 1],
				['text' => '新建促销活动', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '促销活动管理']

			);

			$content->body(view('store-backend::promotion.discount.create', compact('category')));
		});
	}

	public function edit($id)
	{
		$discount = ElDiscount::find($id);
		$category = $this->categoryRepository->getLevelCategory(0);

		return LaravelAdmin::content(function (Content $content) use ($discount, $category) {

			$content->header('编辑促销活动');

			$content->breadcrumb(
				['text' => '促销活动管理', 'url' => 'store/promotion/discount', 'no-pjax' => 1],
				['text' => '编辑促销活动', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '促销活动管理']

			);

			$content->body(view('store-backend::promotion.discount.edit', compact('discount', 'category')));
		});
	}

	public function store(Request $request)
	{
		$base                    = $request->input('base');
		$rules                   = $request->input('rules');
		$action                  = $request->input('action');
		$point_action            = $request->input('point-action');
		$base['per_usage_limit'] = !empty($base['per_usage_limit']) ? $base['per_usage_limit'] : $base['usage_limit'];

		$validator = $this->validationForm();
		if ($validator->fails()) {
			$warnings     = $validator->messages();
			$show_warning = $warnings->first();

			return $this->ajaxJson(false, [], 404, $show_warning);
		}

		if (!$action['configuration'] AND !$point_action['configuration']) {
			return $this->ajaxJson(false, [], 404, '请至少设置一种优惠动作');
		}

		try {
			DB::beginTransaction();
			if (!$discount = $this->discountService->saveData($base, $action, $rules, 0)) {
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
		$rules   = [
			'base.title'       => 'required',
			'base.usage_limit' => 'required | integer',
			'base.starts_at'   => 'required | date',
			'base.ends_at'     => 'required | date | after:base.starts_at',
		];
		$message = [
			"required"           => ":attribute 不能为空",
			"base.ends_at.after" => ':attribute 不能早于活动开始时间',
			"integer"            => ':attribute 必须是整数',
		];

		$attributes = [
			"base.title"       => '活动名称',
			"base.usage_limit" => '使用数量',
			"base.starts_at"   => '开始时间',
			"base.ends_at"     => '结束时间',
		];

		$validator = Validator::make(
			request()->all(),
			$rules,
			$message,
			$attributes
		);

		return $validator;
	}

	/**
	 * 使用记录
	 *
	 * @return mixed
	 */
	public function useRecord()
	{
		$condition = $this->usedCondition();
		$where     = $condition[1];
		$time      = $condition[0];
		$id        = request('id');

		$discounts = $this->orderAdjustmentRepository->getOrderAdjustmentHistory($where, 20, $time);

		return LaravelAdmin::content(function (Content $content) use ($discounts, $id) {

			$content->header('促销活动使用记录');

			$content->breadcrumb(
				['text' => '促销活动管理', 'url' => 'store/promotion/discount', 'no-pjax' => 1],
				['text' => '促销活动使用记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '促销活动管理']

			);

			$content->body(view('store-backend::promotion.discount.use_record', compact('discounts', 'id')));
		});

	}

	private function usedCondition()
	{
		$time  = [];
		$where = [];

		if ($id = request('id')) {
			$where['origin_id'] = $id;
		}

		if (!empty(request('order_no'))) {
			$where['order_no'] = ['like', '%' . request('order_no') . '%'];
		}

		if (!empty(request('etime')) && !empty(request('stime'))) {
			$where['created_at'] = ['<=', request('etime')];
			$time['created_at']  = ['>=', request('stime')];
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
	 *
	 * @return mixed
	 */
	public function getUsedExportData()
	{
		$page  = request('page') ? request('page') : 1;
		$limit = request('limit') ? request('limit') : 20;
		$type  = request('type');

		$condition = $this->usedCondition();
		$where     = $condition[1];
		$time      = $condition[0];

		$coupons  = $this->orderAdjustmentRepository->getOrderAdjustmentHistory($where, $limit, $time);
		$lastPage = $coupons->lastPage();
		$coupons  = $this->discountService->searchAllOrderAdjustmentHistoryExcel($coupons);

		if ($page == 1) {
			session(['export_discount_used_cache' => generate_export_cache_name('export_discount_used_cache_')]);
		}
		$cacheName = session('export_discount_used_cache');

		if ($this->cache->has($cacheName)) {
			$cacheData = $this->cache->get($cacheName);
			$this->cache->put($cacheName, array_merge($cacheData, $coupons), 300);
		} else {
			$this->cache->put($cacheName, $coupons, 300);
		}

		if ($page == $lastPage) {
			$title = ['促销说明', '订单编号', '订单总金额', '订单状态', '用户名', '使用时间'];

			return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'coupon_used_data_']);
		} else {
			$url_bit = route('admin.promotion.discount.getUsedExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));

			return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
		}
	}

}