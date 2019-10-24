<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers\MicroPage;


use DB;
use iBrand\Backend\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use GuoJiangClub\Component\Advert\Models\MicroPageAdvert;
use GuoJiangClub\Component\Advert\Models\Advert;
use GuoJiangClub\Component\Advert\Models\AdvertItem;
use GuoJiangClub\Component\Advert\Repositories\AdvertRepository;
use GuoJiangClub\Component\Advert\Repositories\AdvertItemRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CategoryRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\MicroPageRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\DiscountRepository;
use Carbon\Carbon;


class CompoentController extends Controller
{
    protected $advertRepository;

    protected $advertItemRepository;

    protected $goodsRepository;

    protected $categoryRepository;

    protected $microPageRepository;

    protected $discountRepository;

    protected $advert;

    public function __construct(AdvertRepository $advertRepository,
                                AdvertItemRepository $advertItemRepository,
                                GoodsRepository $goodsRepository,
                                CategoryRepository $categoryRepository,
                                MicroPageRepository $microPageRepository,
                                DiscountRepository $discountRepository,
                                Advert $advert


    )
    {
        $this->advertRepository = $advertRepository;

        $this->advertItemRepository = $advertItemRepository;

        $this->goodsRepository = $goodsRepository;

        $this->categoryRepository = $categoryRepository;

        $this->microPageRepository = $microPageRepository;

        $this->discountRepository = $discountRepository;

        $this->advert = $advert;

    }

    public function index($type = 'micro_page_componet_slide')
    {

        $name = request('name');

        $limit = request('limit') ? request('limit') : 5;

        switch ($type) {
            case 'micro_page_componet_seckill':
                $associate_with = ['goods', 'seckill'];
                break;
            case 'micro_page_componet_groupon':
                $associate_with = ['goods'];
                break;
            default:
                $associate_with = [];
        }

        $lists = $this->getListsByType($type, $limit, $name, $associate_with);


        return LaravelAdmin::content(function (Content $content) use ($lists, $type) {

            $content->header('模块管理');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '模块管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模块管理']

            );

            $server_time = Carbon::now()->toDateTimeString();

            $content->body(view('store-backend::micro_page.compoent.' . $type . '.index', compact('lists', 'type', 'server_time')));
        });
    }


    protected function getListsByType($type, $limit = 15, $name = null, $associate_with = [])
    {

        $query = $this->advert;

        if ($name) {

            $query = $query->where('name', 'like', '%' . $name . '%');
        }

        if (in_array($type, ['micro_page_componet_cube'])) {

            $query = $query->where('type', 'like', '%' . $type . '%');

        } else {

            $query = $query->where('type', $type);
        }

        return $query->orderBy('created_at', 'desc')->with(['item' => function ($query) use ($associate_with) {

            if (count($associate_with) > 0) {

                foreach ($associate_with as $with) {

                    $query = $query->with('associate.' . $with);
                }

            } else {

                $query = $query->with('associate');
            }


            return $query->whereNull('parent_id')->where('status', 1)->orderBy('sort')->get();

        }])->paginate($limit);
    }

    public function create($type)
    {

        $header = request('header');

        if (empty($header)) {

            return redirect()->route('admin.setting.micro.page.compoent.index', 'micro_page_componet_slide');
        }

        return LaravelAdmin::content(function (Content $content) use ($type, $header) {

            $content->header('创建' . $header . '模块');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '模块管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模块管理']

            );

            $advert_id = null;

            $content->body(view('store-backend::micro_page.compoent.' . $type . '.create', compact('advert_id', 'type', 'header')));
        });

    }

    public function edit($type, $code)
    {

        $associate_with = [];

        $header = request('header');

        if (empty($header)) {

            return redirect()->route('admin.setting.micro.page.compoent.index', 'micro_page_componet_slide');
        }

        switch ($type) {
            case 'micro_page_componet_seckill':
                $associate_with = ['goods', 'seckill'];
                break;
            case 'micro_page_componet_groupon':
                $associate_with = ['goods'];
                break;
            default:
                $associate_with = [];
        }

        $advertItems = $this->advertItemRepository->getItemsByCode($code, $associate_with);
//        dd($advertItems);

        $advert = $this->advert->where('code', $code)->first();

        return LaravelAdmin::content(function (Content $content) use ($advert, $advertItems, $code, $type, $header) {

            $content->header('编辑' . $header . '模块');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '模块管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模块管理']

            );

            $advert_id = $advert->id;

            $server_time = Carbon::now()->toDateTimeString();

            $content->body(view('store-backend::micro_page.compoent.' . $type . '.edit', compact('header', 'type', 'code', 'advert', 'advertItems', 'advert_id', 'server_time')));
        });

    }


    /**
     * 删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {

        $advertCount = MicroPageAdvert::where('advert_id', $id)->whereNull('deleted_at')->count();

        if ($advertCount) {

            return $this->ajaxJson(false, [], 400, '删除失败，有微页面已使用该模块');
        }

        $this->advertRepository->delete($id);

        AdvertItem::where('advert_id', $id)->delete();

        return $this->ajaxJson();

    }

    /**
     * 创建
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        $input = request('input');

        $advert_name = request('advert_name');

        $advert_title = request('advert_title');

        $is_show_title = request('is_show_title') ? request('is_show_title') : 0;

        $type = request('type');

        try {

            DB::beginTransaction();

            $code = build_order_no('AD');

            $advert = Advert::create(['name' => $advert_name, 'title' => $advert_title, 'is_show_title' => $is_show_title, 'code' => $code, 'type' => $type]);

            foreach ($input as $item) {

                if (isset($item['limit'])) {

                    unset($item['limit']);
                }

                if ($type == 'micro_page_componet_goods_group') {


                    if ($item['type'] == 'micro_page_componet_goods_group') {

                        $associate_id = $item['associate_id'];
                        unset($item['associate_id']);

                        $associate_type = $item['associate_type'];
                        unset($item['associate_type']);

                        $advert_item = $advert->addAdvertItem($item);

                        foreach ($associate_id as $k => $id) {
                            $citem = $item;
                            $citem['associate_id'] = $id;
                            $citem['sort'] = $k + 1;
                            $citem['associate_type'] = $associate_type;
                            unset($citem['name']);
                            unset($citem['meta']);
                            $advert_item->addChildren($citem);
                        }


                    } else {

                        $input = $item['input'];

                        unset($item['input']);

                        $advert_item = $advert->addAdvertItem($item);

                        foreach ($input as $citem) {

                            $advert_item->addChildren($citem);
                        }

                    }


                } else {

                    $advert->addAdvertItem($item);
                }

            }

            DB::commit();

            return $this->ajaxJson();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return $this->ajaxJson(false, 400, '保存失败');

        }

    }

    /**
     * 更新
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $input = request('input');

        $advert_id = request('advert_id');

        $advert_name = request('advert_name');

        $advert_title = request('advert_title');

        $is_show_title = request('is_show_title') ? request('is_show_title') : 0;


        try {

            DB::beginTransaction();

            $advert = Advert::find($advert_id);

            $advert->name = $advert_name;

            $advert->title = $advert_title;

            $advert->is_show_title = $is_show_title;

            if (request('type')) {

                $advert->type = request('type');
            }

            $advert->save();

            AdvertItem::where('advert_id', $advert_id)->delete();

            foreach ($input as $item) {

                if (isset($item['limit'])) {

                    unset($item['limit']);
                }

                if (request('type') == 'micro_page_componet_goods_group') {

                    if ($item['type'] == 'micro_page_componet_goods_group') {

                        $associate_id = $item['associate_id'];
                        unset($item['associate_id']);

                        $associate_type = $item['associate_type'];
                        unset($item['associate_type']);

                        $advert_item = $advert->addAdvertItem($item);

                        foreach ($associate_id as $k => $id) {
                            $citem = $item;
                            $citem['associate_id'] = $id;
                            $citem['sort'] = $k + 1;
                            $citem['associate_type'] = $associate_type;
                            unset($citem['name']);
                            unset($citem['meta']);
                            $advert_item->addChildren($citem);
                        }


                    } else {

                        $input = $item['input'];

                        unset($item['input']);

                        $advert_item = $advert->addAdvertItem($item);

                        foreach ($input as $citem) {

                            $advert_item->addChildren($citem);
                        }

                    }

                } else {

                    $advert->addAdvertItem($item);
                }

            }

            DB::commit();

            return $this->ajaxJson();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return $this->ajaxJson(false, 400, '保存失败');

        }

    }


    public function getGoodsData()
    {

        $limit = request('limit') ? request('limit') : 5;

        $goods = $this->goodsRepository->scopeQuery(function ($query) {

            $query = $query->where('is_del', 0);

            if (request('title')) {

                $query = $query->where('name', 'like', '%' . request('title') . '%');

            }
            return $query->orderBy('updated_at', 'desc');

        })->paginate($limit);


        if (request('type') == 'micro_page_componet_goods_group') {

            return view('store-backend::micro_page.compoent.common.goodsGroupList', compact('goods'));
        }


        return view('store-backend::micro_page.compoent.common.goodsList', compact('goods'));
    }


    public function getCategorysData()
    {

        $categorys = $this->categoryRepository->getLevelCategory(0, $pid = 0, $html = '&nbsp;&nbsp;&nbsp;', $dep = '');

        return view('store-backend::micro_page.compoent.common.categorys', compact('categorys'));
    }

    public function getPagesData()
    {

        $pages = $this->microPageRepository->scopeQuery(function ($query) {

            if (request('title')) {

                $query = $query->where('name', 'like', '%' . request('title') . '%');

            }
            return $query->orderBy('updated_at', 'desc');

        })->paginate(5);


        return view('store-backend::micro_page.compoent.common.pagesList', compact('pages'));
    }


    public function getCouponsData()
    {
        $coupons = $this->discountRepository->scopeQuery(function ($query) {

            if (request('title')) {

                $query = $query->where('title', 'like', '%' . request('title') . '%');

            }

            $query = $query->where('coupon_based', 1)->where('status', 1)->where('ends_at', '>', Carbon::now());

            return $query->orderBy('updated_at', 'desc');

        })->paginate(5);


        return view('store-backend::micro_page.compoent.common.coupontsList', compact('coupons'));
    }


    public function modelCoupons()
    {
        $coupons = $this->discountRepository->scopeQuery(function ($query) {

            $query = $query->where('coupon_based', 1)->where('status', 1)->where('ends_at', '>', Carbon::now());

            return $query->orderBy('updated_at', 'desc');

        })->paginate(5);


        $coupon_ = null;

        if (request('coupon_id')) {

            $coupon_ = $this->discountRepository->findByField('id', request('coupon_id'))->first();

        }

        return view('store-backend::micro_page.compoent.common.model.coupons', compact('coupons', 'coupon_'));

    }

    public function modelGoods()
    {
        $limit = request('limit') ? request('limit') : 5;

        $goods = $this->goodsRepository->scopeQuery(function ($query) {

            $query = $query->where('is_del', 0);

            return $query->orderBy('updated_at', 'desc');
        })->paginate($limit);

        $goods_ = null;

        $goods_items = null;

        if (request('goods_id')) {

            $goods_ = $this->goodsRepository->findByField('id', request('goods_id'))->first();

        }

        $goods_items = [];

        if (request('goods_ids')) {

            $goods_ids = explode(',', request('goods_ids'));

            foreach ($goods_ids as $k => $id) {

                $goods_items[] = $this->goodsRepository->findByField('id', $id)->first();

                $goods_items = collect($goods_items);

            }
        }


        if (request('type') == 'micro_page_componet_goods_group') {

            return view('store-backend::micro_page.compoent.common.model.goods_group', compact('goods', 'goods_', 'goods_items'));
        }


        $type = request('type');

        return view('store-backend::micro_page.compoent.common.model.goods', compact('goods', 'goods_', 'goods_items', 'type'));

    }

    public function modelCategorys()
    {

        $categorys = $this->categoryRepository->getLevelCategory(0, $pid = 0, $html = '&nbsp;&nbsp;&nbsp;', $dep = '');

        if (request('type') == 'micro_page_componet_category') {

            return view('store-backend::micro_page.compoent.common.model.category_goods', compact('categorys'));
        }

        return view('store-backend::micro_page.compoent.common.model.categorys', compact('categorys'));

    }

    public function modelPages()
    {
        $pages = $this->microPageRepository->scopeQuery(function ($query) {
            return $query->orderBy('updated_at', 'desc');
        })->paginate(5);

        $page_ = null;

        if (request('page_id')) {

            $page_ = $this->microPageRepository->findByField('id', request('page_id'))->first();
        }
        return view('store-backend::micro_page.compoent.common.model.pages', compact('pages', 'page_'));

    }

    public function modelImages()
    {

        return view('store-backend::micro_page.compoent.common.model.images');
    }

}
