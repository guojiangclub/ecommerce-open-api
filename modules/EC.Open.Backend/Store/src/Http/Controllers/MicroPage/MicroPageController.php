<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers\MicroPage;

use DB;
use iBrand\Backend\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use GuoJiangClub\Component\Advert\Models\MicroPage;
use GuoJiangClub\Component\Advert\Models\MicroPageAdvert;
use GuoJiangClub\Component\Advert\Repositories\AdvertRepository;
use GuoJiangClub\Component\Advert\Models\Advert;

class MicroPageController extends Controller
{
    protected $microPage;

    protected $microPageAdvert;

    protected $advertRepository;

    protected $advert;

    public function __construct(MicroPage $microPage,
                                microPageAdvert $microPageAdvert,
                                AdvertRepository $advertRepository,
                                Advert $advert)
    {
        $this->microPage = $microPage;

        $this->microPageAdvert = $microPageAdvert;

        $this->advertRepository = $advertRepository;

        $this->advert = $advert;
    }


    public function index()
    {

        $name = request('name');

        $limit = request('limit');

        $query = $this->microPage;

        if ($name) {

            $query = $query->where('name', 'like', '%' . $name . '%');
        }

        $lists = $query->where('type', 'default')->orderBy('page_type', 'desc')->orderBy('created_at', 'desc')->paginate($limit);

        return LaravelAdmin::content(function (Content $content) use ($lists) {

            $content->header('微页面');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '微页面', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '微页面']

            );

            $content->body(view('store-backend::micro_page.page.index', compact('lists')));
        });

    }

    public function store()
    {

        try {

            DB::beginTransaction();

            $microPage = $this->microPage->create(['name' => request('name'),
                'type' => 'default', 'code' => build_order_no('PAGE')]);

            $pages = 'pages/index/index/index';

            $microPage->link = $pages . '?id=' . $microPage->code;


            $microPage->save();

            DB::commit();

            return $this->ajaxJson();

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::info($e);

            return $this->ajaxJson(false, 400, '保存失败');

        }


    }

    public function delete($id)
    {

        $res = $this->microPage->find($id);

        if ($res->delete()) {

            MicroPageAdvert::where('micro_page_id', $id)->delete();

            return $this->ajaxJson();
        }

        return $this->ajaxJson(false, [], 400, ' 删除失败');

    }


    public function edit($id)
    {
        $page = $this->microPage->find($id);

        $adverts = $this->microPageAdvert->where('micro_page_id', $id)->with('advert')->orderBy('sort')->get();


        return LaravelAdmin::content(function (Content $content) use ($page, $id, $adverts) {

            $content->header('微页面编辑');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '微页面', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '微页面']

            );

            //幻灯片
            $micro_page_componet_slide = $this->advert->where('type', 'micro_page_componet_slide')->where('status', 1)->get();
            //优惠券
            $micro_page_componet_coupon = $this->advert->where('type', 'micro_page_componet_coupon')->where('status', 1)->get();
            //快捷导航
            $micro_page_componet_nav = $this->advert->where('type', 'micro_page_componet_nav')->where('status', 1)->get();
            //魔方
            $micro_page_componet_cube = $this->advert->where('type', 'like', '%' . 'micro_page_componet_cube' . '%')->where('status', 1)->get();
            //秒杀
            $micro_page_componet_seckill = $this->advert->where('type', 'micro_page_componet_seckill')->where('status', 1)->get();
            //集call
            $micro_page_componet_free_event = $this->advert->where('type', 'micro_page_componet_free_event')->where('status', 1)->get();
            //拼团
            $micro_page_componet_groupon = $this->advert->where('type', 'micro_page_componet_groupon')->where('status', 1)->get();
            //套餐
            $micro_page_componet_suit = $this->advert->where('type', 'micro_page_componet_suit')->where('status', 1)->get();
            //分类商品
            $micro_page_componet_category = $this->advert->where('type', 'micro_page_componet_category')->where('status', 1)->get();
            //商品分组
            $micro_page_componet_goods_group = $this->advert->where('type', 'micro_page_componet_goods_group')->where('status', 1)->get();

            $microPageAdvertCount = MicroPageAdvert::where('micro_page_id', $id)->count();

            $action = $microPageAdvertCount ? 'edit' : 'create';

            $content->body(view('store-backend::micro_page.page.edit', compact('page', 'id', 'adverts',
                'action',
                'micro_page_componet_slide',
                'micro_page_componet_nav',
                'micro_page_componet_cube',
                'micro_page_componet_seckill',
                'micro_page_componet_free_event',
                'micro_page_componet_groupon',
                'micro_page_componet_suit',
                'micro_page_componet_goods_group',
                'micro_page_componet_category',
                'micro_page_componet_coupon'

            )));
        });
    }


    public function updateMicroPageAd($id)
    {

        $input = request()->except('_token');

        try {

            DB::beginTransaction();

            MicroPageAdvert::where('micro_page_id', $id)->delete();

            foreach ($input as $item) {

                MicroPageAdvert::create($item);
            }

            DB::commit();

            return $this->ajaxJson();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return $this->ajaxJson(false, 400, '保存失败');

        }
    }

    public function setIndexPage($id)
    {

        if ($page = $this->microPage->where('page_type', 2)->first()) {

            $page->page_type = 1;

            $page->save();
        };

        $index_page = $this->microPage->find($id);

        $index_page->page_type = 2;

        $index_page->save();

        return $this->ajaxJson();

    }


    public function setCategoryPage($id)
    {

        if ($page = $this->microPage->where('page_type', 3)->first()) {

            $page->page_type = 1;

            $page->save();
        };

        $index_page = $this->microPage->find($id);

        $index_page->page_type = 3;

        $index_page->save();

        return $this->ajaxJson();

    }


    public function update()
    {
        $page = $this->microPage->find(request('id'));

        $page->name = request('name');

        $page->save();

        return $this->ajaxJson();

    }

    public function getAdvertByType()
    {
        $ad = $this->advertRepository->findWhere(['type' => request('type'), 'status' => 1]);

        if ($ad->count()) {

            return $this->ajaxJson(true, $ad);
        }

        return $this->ajaxJson();
    }
}
