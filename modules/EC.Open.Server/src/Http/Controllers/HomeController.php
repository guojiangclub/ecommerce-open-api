<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Server\Http\Controllers;

use iBrand\Component\Advert\Repositories\AdvertItemRepository;
use iBrand\EC\Open\Core\Services\GoodsService;
use iBrand\Component\Advert\Models\MicroPage;
use iBrand\Component\Advert\Models\MicroPageAdvert;
use DB;

class HomeController extends Controller
{
    private $advertItem;

    protected $microPage;

    protected $microPageAdvert;

    public function __construct(
        AdvertItemRepository $advertItemRepository
        , MicroPage $microPage
        , microPageAdvert $microPageAdvert
    )
    {
        $this->advertItem = $advertItemRepository;

        $this->microPage = $microPage;

        $this->microPageAdvert = $microPageAdvert;

    }

    public function index()
    {
//        $carousels = $this->advertItem->getItemsByCode('home.carousel');
//        $categories = $this->advertItem->getItemsByCode('home.categories');
//
//        $goodsService = app(GoodsService::class);
//
//        $boysGoods = $goodsService->getGoodsByCategoryId(3)->where('is_del', 0)->take(6);
//
//        $boyCategory = ['name' => '男童 T恤/衬衫', 'link' => '/pages/store/list/list?c_id=3', 'items' => array_values($boysGoods->toArray())];
//
//        $girlGoods = $goodsService->getGoodsByCategoryId(6)->where('is_del', 0)->take(6);
//
//        $girlCategory = ['name' => '女童 T恤/衬衫', 'link' => '/pages/store/list/list?c_id=6', 'items' => array_values($girlGoods->toArray())];
//
//        return $this->success(compact('carousels', 'categories', 'boyCategory', 'girlCategory'));
    }

    public function category()
    {

        $microPage = $this->microPage->where('page_type', 3)->first();

        if (!$microPage) return $this->success();

        $microPageAdverts = $this->microPageAdvert->where('micro_page_id', $microPage->id)
            ->with(['advert' => function ($query) {

                return $query = $query->where('status', 1);
            }])
            ->orderBy('sort')->get();

        if ($microPageAdverts->count()) {

            $i = 0;

            foreach ($microPageAdverts as $key => $item) {

                if ($item->advert_id > 0) {

                    $data['pages'][$i]['name'] = $item->advert->type;

                    $data['pages'][$i]['title'] = $item->advert->title;

                    $data['pages'][$i]['is_show_title'] = $item->advert->is_show_title;

                    $advertItem = $this->advertItem->getItemsByCode($item->advert->code, []);

                    $data['pages'][$i]['value'] = array_values($advertItem);

                }

                $i++;

            }

        }

        $data['micro_page'] = $microPage;

        return $this->success($data);


//        $items = $this->advertItem->getItemsByCode('home.categories');
//
//        return $this->success($items);
    }


}
