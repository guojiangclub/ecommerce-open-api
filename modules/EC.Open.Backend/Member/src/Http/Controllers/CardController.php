<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Http\Controllers;

use iBrand\EC\Open\Backend\Member\Repository\CardRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Response;

class CardController extends Controller
{
    protected $cardRepository;
    protected $cache;

    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
        $this->cache = cache();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->createConditions();
        $where = $data[0];
        $time = $data[1];

        $cardList = $this->cardRepository->getCardsPaginated($where, 20, $time);
        $count = count($cardList);

        //return view('member-backend::card.index', compact('cardList', 'count'));

        return LaravelAdmin::content(function (Content $content) use ($cardList,$count) {
            $content->header('会员卡列表');

            $content->breadcrumb(
                ['text' => '会员卡列表', 'url' => 'member/card', 'no-pjax' => 1,'left-menu-active'=>'会员卡管理']
            );

            $content->body(view('member-backend::card.index', compact('cardList', 'count')));
        });
    }

    public function exportExcel(Request $request)
    {
        $time = [];
        $where = [];

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        $cardList = $this->cardRepository->getCardsPaginated($where, 0, $time);
        $title = ['会员卡号', '用户名', '手机', '生日', '注册时间', 'uid'];

        return $this->cardRepository->exportExcel('card_', $cardList, $title);
    }

    public function download($url)
    {
        // return Response::download(storage_path()."/exports/$url");
        return response()->download(storage_path()."/exports/$url")->deleteFileAfterSend(true);
    }

    public function edit($id)
    {
        $card = $this->cardRepository->find($id);

        return view('member-backend::card.edit', compact('card'));
    }

    public function update(Request $request, $id)
    {
        $card = $this->cardRepository->find($id);
        $data = $request->except('_token');
        if (!$data['name']) {
            unset($data['name']);
        }
        if (!$data['birthday']) {
            unset($data['birthday']);
        }

        $result = $this->cardRepository->scopeQuery(function ($query) use ($data, $card) {
            return $query->where('number', $data['number'])->where('id', '<>', $card->id);
        })->all();

        if (count($result) > 0) {
            return $this->ajaxJson(false);
        }
        $this->cardRepository->update($data, $id);

        return $this->ajaxJson(true);
    }

    /**
     * 获取导出数据.
     *
     * @return mixed
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 50;
        $type = request('type');
        $condition = $this->createConditions();
        $where = $condition[0];
        $time = $condition[1];
        $cards = $this->cardRepository->getCardsPaginated($where, $limit, $time);
        $lastPage = $cards->lastPage();
        $cardExcelData = $this->cardRepository->formatToExcelData($cards);

        if (1 == $page) {
            session(['export_cards_cache' => generate_export_cache_name('export_cards_cache_')]);
        }
        $cacheName = session('export_cards_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $cardExcelData), 300);
        } else {
            $this->cache->put($cacheName, $cardExcelData, 300);
        }

        if ($page == $lastPage) {
            $title = ['会员卡号', '用户名', '手机', '生日', '注册时间', 'uid'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'cards_data_']);
        }
        $url_bit = route('admin.card.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
    }

    protected function createConditions()
    {
        $where = [];
        $time = [];
        if (!empty(request('field'))) {
            $where[request('field')] = ['like', '%'.request('value').'%'];
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

        return [$where, $time];
    }
}
