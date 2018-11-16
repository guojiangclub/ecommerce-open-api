<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\EC\Open\Backend\Store\Model\Advertisement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Repositories\AdvertisementRepository;
use iBrand\EC\Open\Backend\Store\Repositories\AdvertisementItemRepository;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsRepository;
use  iBrand\EC\Open\Backend\Store\Model\AdvertisementItem;
use Route;
use Validator;


class AdvertisementItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $advertisementRepository;
    protected $advertisementItemRepository;
    protected $goodsRepository;

    public function __construct(AdvertisementRepository $advertisementRepository
        , AdvertisementItemRepository $advertisementItemRepository
        , GoodsRepository $goodsRepository
    )
    {
        $this->advertisementRepository = $advertisementRepository;
        $this->advertisementItemRepository = $advertisementItemRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function index()
    {
        if (empty(request('ad_id'))) {
            return redirect()->route('ad.index');
        }
        $ad_items = $this->advertisementItemRepository->orderBy('sort')->findByField('ad_id', request('ad_id'))->all();
        $newdata = [];
        if (count($ad_items) > 0) {
            foreach ($ad_items as $k => $item) {
                if (empty($item->meta)) {
                    $newdata[] = $item;
                }
                if (isset($item->meta) && !empty($item->meta)) {
                    $meta = json_decode($item->meta, true);
                    if (!isset($meta['pid'])) {
                        $newdata[] = $item;
                    }
                }
            }

        }
        $ad_items = $newdata;


        return view("store-backend::advertisement.ad_item.index", compact('ad_items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('store-backend::advertisement.ad_item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('id', '_token', 'file');

        //验证排序
        $rules = array(
            'sort' => "required | integer | min:0",

        );
        $message = array(
            "required" => ":attribute 不能为空",
            "integer" => ":attribute 必须是整数",
            "min" => ":attribute 非负数最小为0"
        );
        $attributes = array(
            "sort" => "排序",
        );

        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();

            return response()->json(['status' => false
                    , 'error_code' => 0
                    , 'error' => $warning
                ]
            );
        }

        if (request('id')) {
            $this->advertisementItemRepository->update($input, request('id'));
            $id = request('id');
        } else {
            $ad = $this->advertisementItemRepository->create($input);
            $id = $ad->id;
        }

        return response()->json(['status' => true
            , 'error_code' => 0
            , 'error' => ''
            , 'ad_id' => request('ad_id')
            , 'data' => $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ad_id = request('ad_id');
        $child = [];
        $aditem_list = $this->advertisementItemRepository->findByField('id', $id)->first();
        if ($aditem_list->meta) {
            $meta = json_decode($aditem_list->meta, true);
            if (isset($meta['child']) && count($meta['child']) > 0) {
                $child = $this->advertisementItemRepository->orderBy('sort')->findWhereIn('id', $meta['child']);
            }
        }
        return view('store-backend::advertisement.ad_item.edit', compact('aditem_list', 'ad_id', 'child'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ad = $this->advertisementItemRepository->findByField('id', $id)->first();
        if (!empty($ad->meta)) {
            $meta = json_decode($ad->meta, true);
            if (isset($meta['child']) && count($meta['child']) > 0) {
                foreach ($meta['child'] as $item) {
                    $adItem = $this->advertisementItemRepository->findByField('id', $item)->first();
                    if (!is_null($adItem)) {
                        return redirect()->back()->with('message', '推广位下非空删除失败');
                    }
                }
            }

            if (isset($meta['pid'])) {
                $pid = $meta['pid'];
                $adItem = $this->advertisementItemRepository->findByField('id', $pid)->first();
                $newmeta = json_decode($adItem->meta, true);
                $meta = [];
                if (isset($newmeta['child']) && count($newmeta['child']) > 0) {
                    foreach ($newmeta['child'] as $item) {
                        if ($item != $id) {
                            $meta[] = $item;
                        }
                    }
                }
                $type = count($meta) == 0 ? '' : $adItem->type;
                $newmeta['child'] = $meta;
                $newmeta = json_encode($newmeta);
                $res = $this->advertisementItemRepository->update(['meta' => $newmeta, 'type' => $type], $pid);

            }

            $this->advertisementItemRepository->delete($id);
            return redirect()->back();
        }
        $this->advertisementItemRepository->delete($id);
        return redirect()->back();

    }

    public function promoteGoods()
    {

        $goods = $this->goodsRepository->scopeQuery(function ($query) {
            $query = $query->where(['is_del' => 0]);
            return $query->orderBy('updated_at', 'desc');
        })->paginate(15);

        $link = '';
        $link = settings('ad_store_detail_url') ? settings('ad_store_detail_url') : '';
        if (empty($link)) {
            if (Route::has('pc.store.detail')) {
                $link = route('pc.store.detail', ['id' => '###']);
                settings()->setSetting(['ad_store_detail_url' => $link]);
            }
        }
        return view('store-backend::advertisement.ad_item.includes.promoteGoods', compact('goods', 'link'));

    }

    public function getPromoteGoodsData()
    {
        $goods = $this->goodsRepository->scopeQuery(function ($query) {
            $query = $query->where(['is_del' => 0]);
            return $query->orderBy('updated_at', 'desc');
        })->paginate(15);

        if (!empty(request('title'))) {
            $where['is_del'] = 0;
            $where['name'] = ['like', '%' . request('title') . '%'];
            $goods = $this->goodsRepository->getGoodsPaginated($where, $where_ = [], $ids = [], $limit = 15, $order_by = 'updated_at', $sort = 'desc');
        }
        return view('store-backend::advertisement.ad_item.includes.goodsList', compact('goods'));
    }


    public function AddGoodsPromote()
    {
        if (count(request('ids')) > 0 && !empty(request('ad_id'))) {
            $ad_id = request('ad_id');
            $Ids = request('ids');
            $meta = [];
            $data = [];
            $sort = 0;
            $link = settings('ad_store_detail_url') ? settings('ad_store_detail_url') : '';

            if ($ads = $this->advertisementItemRepository->orderBy('sort', 'desc')->findWhere(['ad_id' => $ad_id])->first()) {
                $sort = empty($ads->sort) ? 0 : $ads->sort;
            }

            foreach ($Ids as $k => $item) {
                if (count($item) > 0) {
                    foreach ($item as $kitem) {
                        $goods = $this->goodsRepository->find($kitem);
                        if ($goods) {
                            $sort += 1;
                            $data['ad_id'] = $ad_id;
                            empty($link) ? $newLink = '' : $newLink = $this->replaceUrl($link, $goods->id);
                            $data['link'] = $newLink;
                            $data['name'] = $goods->name;
                            $data['image'] = $goods->img;
                            $data['sort'] = $sort;
                            $meta = [
                                'goods' => [
                                    'id' => $goods->id,
                                    'name' => $goods->name,
                                    'img' => $goods->img,
                                    'is_commend' => $goods->is_commend,
                                    'is_old' => $goods->is_old,
                                    'tags' => $goods->tags,
                                    'price' => $goods->min_price,
                                ]
                            ];


                            if (!empty(request('pid'))) {
                                $meta['pid'] = request('pid');
                            }

                            $data['meta'] = json_encode($meta);
//                            $data['link']='';

                            if ($ad = $this->advertisementItemRepository->create($data)) {
                                if (!empty(request('pid'))) {
                                    $pid = request('pid');
                                    $aditem_list = $this->advertisementItemRepository->findByField('id', $pid)->first();
                                    $newmeta = json_decode($aditem_list->meta, true);
                                    $newmeta['child'][] = $ad->id;
                                    $newmeta = json_encode($newmeta);
                                    $res = $this->advertisementItemRepository->update(['meta' => $newmeta, 'type' => 'goods'], $pid);
                                }
                            }

                        }
                    }
                }
            }

            return response()->json(['status' => true
                , 'error_code' => 0
                , 'error' => ''
                , 'ad_id' => $ad_id
            ]);
        }

    }


    //子推广
    public function createChild()
    {
        return view('store-backend::advertisement.ad_item.child.create');
    }


    public function editChild($id)
    {
        $ad_id = request('ad_id');
        $aditem_list = $this->advertisementItemRepository->findByField('id', $id)->first();
        return view('store-backend::advertisement.ad_item.edit', compact('aditem_list', 'ad_id'));
    }


    //子推广
    public function storeChild(Request $request)
    {
        $input = $request->except('id', '_token', 'file', 'pid');

        //验证排序
        $rules = array(
            'sort' => "required | integer | min:0",

        );
        $message = array(
            "required" => ":attribute 不能为空",
            "integer" => ":attribute 必须是整数",
            "min" => ":attribute 非负数最小为0"
        );
        $attributes = array(
            "sort" => "排序",
        );

        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();

            return response()->json(['status' => false
                    , 'error_code' => 0
                    , 'error' => $warning
                ]
            );
        }

        $pid = request('pid');
        if (request('id')) {
            $this->advertisementItemRepository->update($input, request('id'));
            $id = request('id');
        } else {
            if (!empty(request('pid'))) {
                $meta = ['pid' => request('pid')];
                $meta = json_encode($meta);
                $input['meta'] = $meta;
            }
            $ad = $this->advertisementItemRepository->create($input);
            $id = $ad->id;
            if (!empty(request('pid'))) {
                $aditem_list = $this->advertisementItemRepository->findByField('id', $pid)->first();
                $meta = json_decode($aditem_list->meta, true);
                $meta['child'][] = $id;
                $meta = json_encode($meta);
                $res = $this->advertisementItemRepository->update(['meta' => $meta, 'type' => 'image'], $pid);

            }

        }
        return response()->json(['status' => true
            , 'error_code' => 0
            , 'error' => ''
            , 'ad_id' => request('ad_id')
            , 'pid' => $pid
            , 'data' => $id]);
    }


    protected function replaceUrl($str = '', $goods_id)
    {
        if (!empty($str)) {
            if (strpos($str, '###')) {
                return str_replace("###", $goods_id, $str);
            }
        }
        return $str;
    }


    public function SetAdStoreDetailUrl(Request $request)
    {
        $value = request('value') ? request('value') : '';
        if ($value) {
            settings()->setSetting(['ad_store_detail_url' => $value]);
            return '设置成功';
        }
    }


    public function toggleStatus(Request $request)
    {
        $status = request('status');
        $id = request('aid');
        $user = AdvertisementItem::find($id);
        $user->fill($request->only('status'));
        $user->save();
        return response()->json(
            ['status' => true
                , 'code' => 200
                , 'message' => ''
                , 'data' => []]
        );
    }


}
