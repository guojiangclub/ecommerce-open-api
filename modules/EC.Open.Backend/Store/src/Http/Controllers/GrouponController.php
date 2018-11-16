<?php
namespace iBrand\EC\Open\Backend\Store\Http\Controllers;


use iBrand\Backend\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Model\Groupon;
use iBrand\EC\Open\Backend\Store\Model\GrouponItem;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsRepository;
use iBrand\EC\Open\Backend\Store\Repositories\GrouponRepository;
use iBrand\EC\Open\Backend\Store\Service\GoodsService;
use Illuminate\Http\Request;
use Validator;
use DB;
use iBrand\EC\Open\Backend\Store\Service\SpecialGoodsService;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class GrouponController extends Controller
{

    protected $grouponRepository;
    protected $goodsService;
    protected $goodsRepository;
    protected $specialGoodsService;

    public function __construct(GrouponRepository $grouponRepository
        , GoodsService $goodsService
        , GoodsRepository $goodsRepository
        , SpecialGoodsService $specialGoodsService

    )
    {
        $this->grouponRepository = $grouponRepository;
        $this->goodsService = $goodsService;
        $this->goodsRepository = $goodsRepository;
        $this->specialGoodsService = $specialGoodsService;
    }

    public function index()
    {
        $groupon = $this->grouponRepository->getGrouponPaginated();

        return LaravelAdmin::content(function (Content $content) use ($groupon) {

            $content->header('拼团活动列表');

            $content->breadcrumb(
                ['text' => '拼团管理', 'url' => 'store/promotion/groupon', 'no-pjax' => 1],
                ['text' => '拼团活动列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '拼团管理']

            );

            $content->body(view('store-backend::groupon.index', compact('groupon')));
        });

//        return view('store-backend::groupon.index', compact('groupon'));
    }

    public function create()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('新建拼团活动');

            $content->breadcrumb(
                ['text' => '拼团管理', 'url' => 'store/promotion/groupon', 'no-pjax' => 1],
                ['text' => '新建拼团活动', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '拼团管理']

            );

            $content->body(view('store-backend::groupon.create'));
        });

//        return view('store-backend::groupon.create');
    }

    public function edit($id)
    {
        $groupon = $this->grouponRepository->find($id);

        $items = $groupon->items->sortByDesc('sort');
        $num = count($items);
        $ids = implode(',', $items->pluck('goods_id')->toArray());

        return LaravelAdmin::content(function (Content $content) use ($groupon, $num, $ids, $items) {

            $content->header('编辑拼团活动');

            $content->breadcrumb(
                ['text' => '拼团管理', 'url' => 'store/promotion/groupon', 'no-pjax' => 1],
                ['text' => '编辑拼团活动', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '拼团管理']

            );

            $content->body(view('store-backend::groupon.edit', compact('groupon', 'num', 'ids', 'items')));
        });

//        return view('store-backend::groupon.edit', compact('groupon', 'num', 'ids', 'items'));
    }

    public function getSpu()
    {
        return view('store-backend::groupon.includes.modal.getSpu');
    }

    public function getSpuData(Request $request)
    {
        $id = $request->input('id') ? $request->input('id') : 0;
        $ids = [];
        if ($request->input('ids')) {
            $ids = explode(',', $request->input('ids'));
        }

        $where = [];
        $where_ = [];

        $where['is_del'] = ['=', 0];
        $where['is_largess'] = ['=', 0];

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

        $goods = $this->goodsRepository->getGoodsPaginated($where, $where_, $goods_ids, 10)->toArray();

        $goods = $this->specialGoodsService->filterGoodsStatus($goods, $id);

        $goods['ids'] = $ids;

        return $this->ajaxJson(true, $goods);
    }

    /**
     * 展示选择的商品
     */
    public function getSelectGoods()
    {
        $num = request('num') + 1;
        $ids = explode(',', request('ids'));
        $selected = explode(',', request('select'));
        $goods_id = array_merge(array_diff($ids, $selected), array_diff($selected, $ids));
        $goods = $this->goodsRepository->getGoodsPaginated([], [], $goods_id, 0);
        return view('store-backend::groupon.includes.select_goods', compact('goods', 'num'));
    }

    /**
     * 创建活动
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token', 'hour', 'minute', 'goods_name', 'upload_image']);

        $validator = $this->validationForm();
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return $this->ajaxJson(false, [], 404, $show_warning);
        }

        try {
            DB::beginTransaction();
            $check = $this->grouponRepository->checkItems($data['item'], $request->only('goods_name'));

            if ($check) {
                return $this->ajaxJson(false, [], 404, '以下商品已经参与其他拼团杀活动：' . $check);
            }

            $groupon = Groupon::create($data['base']);

            if ($groupon) {
                $groupon->items()->createMany($data['item']);
            }

            DB::commit();

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
            'base.auto_close' => 'required | integer | min:0',
            'base.starts_at' => 'required | date',
            'base.ends_at' => 'required | date | after:base.starts_at',
            'item' => 'required',
            'item.*.groupon_price' => 'required|numeric|min:1',
            'item.*.limit' => 'required | integer|min:1',
            'item.*.rate' => 'required | integer|min:0',
            'item.*.sell_num' => 'required | integer|min:0',
            'item.*.sort' => 'required | integer|min:1',
            'item.*.number' => 'required | integer | min:2',
        );
        $message = array(
            "required" => ":attribute 不能为空",
            "base.ends_at.after" => ':attribute 不能早于活动开始时间',
            "integer" => ':attribute 必须是整数',
            "base.auto_close.min" => ':attribute 不能为负数',
            "numeric" => ':attribute 必须是数值',
            "item.*.groupon_price.min" => ':attribute 不能小于1元',
            "item.*.limit.min" => ':attribute 不能小于1件',
            "item.*.rate.min" => ':attribute 不能为负数',
            'item.*.sell_num.min' => ':attribute 不能为负数',
            'item.*.sort.min' => ':attribute 不能小于1',
            "item.*.number.min" => ':attribute 最小为2',
        );

        $attributes = array(
            "base.title" => '活动名称',
            "base.auto_close" => '订单自动关闭时间',
            "base.starts_at" => '开始时间',
            "base.ends_at" => '领取截止时间',
            "item" => '活动商品',
            'item.*.groupon_price' => '拼团价格',
            'item.*.limit' => '限购数量',
            'item.*.rate' => '佣金比例',
            'item.*.sell_num' => '销量展示',
            'item.*.sort' => '排序',
            "item.*.number" => '成团人数',
        );

        $validator = Validator::make(
            request()->all(),
            $rules,
            $message,
            $attributes
        );

        return $validator;
    }

    /**
     * 更新已开始的活动
     * @param Request $request
     * @return mixed
     */
    public function updateDisable(Request $request)
    {
        $data = $request->only(['id', 'item']);
        $check = $this->grouponRepository->checkItems($data['item'], $request->only('goods_name'), $data['id']);
        if ($check) {
            return $this->ajaxJson(false, [], 404, '以下商品已经参与其他拼团活动：' . $check);
        }

        foreach ($data['item'] as $item) {
            if ($item['sell_num'] < 0 OR !is_numeric($item['sell_num'])) {
                return $this->ajaxJson(false, [], 404, '销量展示 输入有误');
            }

            if ($item['sort'] < 0 OR !is_numeric($item['sort'])) {
                return $this->ajaxJson(false, [], 404, '排序 输入有误');
            }
        }

        try {
            DB::beginTransaction();
            foreach ($data['item'] as $item) {
                $grouponItem = GrouponItem::find($item['id']);
                $grouponItem->status = $item['status'];
                $grouponItem->sort = $item['sort'];
                if ($item['sell_num'] > 0) {
                    $grouponItem->sell_num = $item['sell_num'];
                }

                $grouponItem->save();
            }
            DB::commit();
            return $this->ajaxJson(true, [], 0, '');
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);
            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }

    /**
     * 更新未开始活动
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', 'hour', 'minute', 'goods_name', 'upload_image']);
        // dd($data);
        $validator = $this->validationForm();
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return $this->ajaxJson(false, [], 404, $show_warning);
        }

        try {
            DB::beginTransaction();
            $check = $this->grouponRepository->checkItems($data['item'], $request->only('goods_name'), $data['id']);

            if ($check) {
                return $this->ajaxJson(false, [], 404, '以下商品已经参与其他拼团活动：' . $check);
            }

            $groupon = $this->grouponRepository->update($data['base'], $data['id']);

            if ($groupon) {
                $handleData = $this->grouponRepository->handleUpdateItem($data['item']);

                if (count($handleData['createData']) > 0) {
                    $groupon->items()->createMany($handleData['createData']);
                }
                foreach ($handleData['updateData'] as $item) {
                    $grouponItem = GrouponItem::find($item['id']);
                    $grouponItem->fill($item);
                    $grouponItem->save();
                }
            }

            if ($data['delete_item']) {
                GrouponItem::destroy(explode(',', $data['delete_item']));
            }

            DB::commit();

            return $this->ajaxJson(true, [], 0, '');

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);
            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }

    /**
     * 删除拼团活动
     * @param $id
     */
    public function delete($id)
    {
        $groupon = $this->grouponRepository->find($id);
        if ($groupon->check_status) {
            return $this->ajaxJson(false, [], 404, '活动已开始，不能删除');
        }
        $groupon->delete();
        $groupon->items()->delete();

        return $this->ajaxJson();
    }

}