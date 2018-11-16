<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\EC\Open\Backend\Store\Repositories\GoodsLimitRepository;
use iBrand\Component\Product\Models\Goods;
use iBrand\EC\Open\Backend\Store\Model\GoodsLimit;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use iBrand\Backend\Http\Controllers\Controller;

class GoodsPurchaseController extends Controller
{
    protected $goodsRepository;

    public function __construct(GoodsLimitRepository $goodsRepository)
    {
        $this->goodsRepository = $goodsRepository;
    }

    public function index()
    {
        $status = request('status');
        $criteria['activity'] = $status == 'ACTIVITY' ? 1 : 0;

        $ids = [];
        if ($value = request('value')) {
            $where['name'] = ['like', '%' . $value . '%'];
            $ids = $this->goodsRepository->getGoodsIdsByCriteria($where);
        }

        $goods = $this->goodsRepository->getGoodsPaginate($criteria, $ids);

        return LaravelAdmin::content(function (Content $content) use ($goods, $status) {

            $content->header('商品限购');

            $content->breadcrumb(
                ['text' => '商品限购', 'url' => 'store/limit?status=ACTIVITY', 'no-pjax' => 1],
                ['text' => '限购商品管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品限购']

            );

            $content->body(view('store-backend::goods.index', compact('goods', 'status')));
        });

//		return view("store-backend::goods.index", compact('goods', 'status'));
    }

    /**
     * 同步商品modal
     *
     * @return mixed
     */
    public function syncGoods()
    {
        return view("store-backend::goods.includes.sync_goods");
    }

    /**
     * 同步商品到分销商品表
     */
    public function postSyncGoods()
    {
        $goods = Goods::where('is_del', 0)->paginate(100);
        $lastPage = $goods->lastPage();
        $page = request('page') ? request('page') : 1;
        $url = route('admin.store.goods.limit.postSyncGoods', ['page' => $page + 1]);

        if ($page > $lastPage) {
            return $this->ajaxJson(true, ['status' => 'complete']);
        }

        foreach ($goods as $item) {
            if (!GoodsLimit::where('goods_id', $item->id)->first()) {
                GoodsLimit::create([
                    'goods_id' => $item->id,
                    'activity' => request('activity'),
                    'quantity' => request('quantity') ? request('quantity') : 0,
                    'starts_at' => request('starts_at') ? request('starts_at') : null,
                    'ends_at' => request('ends_at') ? request('ends_at') : null,
                ]);
            }
        }

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url, 'current_page' => $page, 'total' => $lastPage]);
    }

    public function editGoods()
    {
        $goods = GoodsLimit::find(request('id'));

        return view("store-backend::goods.includes.edit_goods", compact('goods'));
    }

    public function saveGoods()
    {
        $goods = GoodsLimit::find(request('id'));
        $goods->activity = request('activity');
        $goods->quantity = request('quantity') ? request('quantity') : 0;
        $goods->starts_at = request('starts_at') ? request('starts_at') : null;
        $goods->ends_at = request('ends_at') ? request('ends_at') : null;
        $goods->save();

        return $this->ajaxJson();
    }

    public function editBatchGoods()
    {
        if (request('ids') == 'all') {
            $ids = request('ids');
        } else {
            $ids = implode(',', request('ids'));
        }

        $type = request('type');
        $value = request('value');
        $status = request('status');

        return view("store-backend::goods.includes.edit_batch_goods", compact('ids', 'type', 'value', 'status'));
    }

    public function saveBatchGoods()
    {
        $ids = request('ids');
        $type = request('type');
        $activity = request('status') == 'ACTIVITY' ? 1 : 0;

        $goods_ids = [];
        if ($value = request('value')) {
            $criteria['name'] = ['like', '%' . $value . '%'];
            $goods_ids = $this->goodsRepository->getGoodsIdsByCriteria($criteria);
        }

        if ($type == 'status') {
            $data = [
                'activity' => request('activity'),
                'quantity' => request('quantity') ? request('quantity') : 0,
                'starts_at' => request('starts_at') ? request('starts_at') : null,
                'ends_at' => request('ends_at') ? request('ends_at') : null,
            ];
        } else {
            $data = ['rate' => request('rate')];
        }

        if ($ids == 'all') {
            if (count($goods_ids) > 0) {
                GoodsLimit::whereIn('goods_id', $goods_ids)->where('activity', $activity)->update($data);
            } else {
                GoodsLimit::where('activity', $activity)->update($data);
            }
        } else {
            $ids = explode(',', request('ids'));
            GoodsLimit::whereIn('id', $ids)->update($data);
        }

        return $this->ajaxJson();
    }

    public function ajaxJson($status = true, $data = [], $code = 200, $message = '')
    {
        return response()->json(
            ['status' => $status
                , 'code' => $code
                , 'message' => $message
                , 'data' => $data]
        );
    }
}