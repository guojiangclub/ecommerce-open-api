<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 2017/3/11
 * Time: 23:04
 */

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers\Promotion;

use GuoJiangClub\EC\Open\Backend\Store\Service\GoodsService;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use iBrand\Backend\Http\Controllers\Controller;
use GuoJiangClub\EC\Open\Backend\Store\Facades\ExcelExportsService;
use Response;

class PublicController extends Controller
{
	protected $goodsRepository;
	protected $goodsService;

	public function __construct(GoodsRepository $goodsRepository
		, GoodsService $goodsService)
	{
		$this->goodsRepository = $goodsRepository;
		$this->goodsService    = $goodsService;
	}

	public function getSpu(Request $request)
	{
		$url    = 'admin.promotion.getSpuData';
		$action = $request->input('action');

		return view('store-backend::promotion.public.modal.getSpu', compact('goods', 'url', 'action'));
	}

	public function getWechatGroup(Request $request)
	{
		$url    = 'admin.promotion.getWechatGroupData';
		$action = $request->input('action');

		return view('store-backend::promotion.public.modal.getWechatGroup', compact('goods', 'url', 'action'));
	}

	public function getSpuData(Request $request)
	{
		$ids    = explode(',', $request->input('ids'));
		$action = $request->input('action');

		$where  = [];
		$where_ = [];

		$where['is_del'] = ['=', 0];

		if (!empty(request('value')) AND request('field') !== 'sku' AND request('field') !== 'category') {
			$where[request('field')] = ['like', '%' . request('value') . '%'];
		}

		if (!empty(request('store_begin')) && !empty(request('store_end'))) {
			$where['store_nums']  = ['>=', request('store_begin')];
			$where_['store_nums'] = ['<=', request('store_end')];
		}

		if (!empty(request('store_begin'))) {
			$where_['store_nums'] = ['>=', request('store_begin')];
		}

		if (!empty(request('store_end'))) {
			$where_['store_nums'] = ['<=', request('store_end')];
		}

		if (!empty(request('price_begin')) && !empty(request('price_end'))) {
			$where[request('price')]  = ['>=', request('price_begin')];
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
		if (count($goods) == 0) {
			$goods['data'] = [];
		}

		$goods['ids'] = $ids;

		return $this->ajaxJson(true, $goods);
	}

	
	// 保存excel到服务器
	public function excelExport()
	{
		$date  = !empty(request()->input('date')) ? request()->input('date') : [];
		$title = !empty(request()->input('title')) ? request()->input('title') : [];
		$name  = !empty(request('name')) ? request('name') : 'Coupons_';

		return ExcelExportsService::createExcelExport($name, $date, $title);
	}

// 导出下载excel文件
	public function download($url)
	{
		return Response::download(storage_path() . "/exports/" . $url);
	}
}