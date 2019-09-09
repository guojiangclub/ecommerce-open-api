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

class HomeController extends Controller
{
    private $advertItem;

    public function __construct(AdvertItemRepository $advertItemRepository)
    {
        $this->advertItem = $advertItemRepository;
    }

    public function index()
    {
        $carousels = $this->advertItem->getItemsByCode('home.carousel');
        $categories = $this->advertItem->getItemsByCode('home.categories');

        $goodsService = app(GoodsService::class);

        $boysGoods = $goodsService->getGoodsByCategoryId(3)->where('is_del', 0)->take(6);

        $boyCategory = ['name' => '男童 T恤/衬衫', 'link' => '/pages/store/list/list?c_id=3', 'items' => array_values($boysGoods->toArray())];

        $girlGoods = $goodsService->getGoodsByCategoryId(6)->where('is_del', 0)->take(6);

        $girlCategory = ['name' => '女童 T恤/衬衫', 'link' => '/pages/store/list/list?c_id=6', 'items' => array_values($girlGoods->toArray())];

        return $this->success(compact('carousels', 'categories', 'boyCategory', 'girlCategory'));
    }

    public function category()
    {
        $items = $this->advertItem->getItemsByCode('home.categories');

        return $this->success($items);
    }
}
