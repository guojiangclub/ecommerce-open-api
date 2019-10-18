<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use DB;
use GuoJiangClub\Component\Category\RepositoryContract as CategoryRepository;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Product\AttributeRelation;
use GuoJiangClub\Component\Product\Models\Attribute;
use GuoJiangClub\Component\Product\Models\Specification;
use GuoJiangClub\Component\Product\Models\SpecificationRelation;
use GuoJiangClub\Component\Product\Models\SpecificationValue;
use GuoJiangClub\Component\Product\Repositories\GoodsRepository;
use GuoJiangClub\EC\Open\Core\Services\DiscountService;
use GuoJiangClub\EC\Open\Server\Transformers\GoodsTransformer;
use iBrand\Miniprogram\Poster\MiniProgramShareImg;
use Storage;
use iBrand\Common\Wechat\Factory;

class GoodsController extends Controller
{
    protected $goodsRepository;
    protected $couponRepository;
    protected $categoryRepository;

    public function __construct(GoodsRepository $goodsRepository,
                                CouponRepository $couponRepository,
                                CategoryRepository $categoryRepository
    )
    {
        $this->goodsRepository = $goodsRepository;
        $this->couponRepository = $couponRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        //1. get sort parameter
        $orderBy = request('orderBy') ? request('orderBy') : 'updated_at';
        $sort = request('sort') ? request('sort') : 'desc';
        $hasFlag = false;

        $categoryGoodsIds = [];
        //2. get category parameter and get all sub categories
        if ($categoryId = request('c_id')) {
            $categoryIds = [];

            $categoryIds = $this->categoryRepository->getSubIdsById($categoryId);
            $goodsCategoryTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_category';
            $categoryGoodsIds = DB::table($goodsCategoryTable)->whereIn('category_id', $categoryIds)->select('goods_id')->distinct()->get()
                ->pluck('goods_id')->toArray();
            $hasFlag = true;
        }

        //3. get specification parameters
        //TODO: 需要改造这里的业务逻辑
        $specGoodIds = $categoryGoodsIds;
        if ($specArray = request('specs')) {
            $k = 0;
            $tempIds = [];
            foreach ($specArray as $key => $item) {
                if ('size' == $key) {
                    $tempIds[$k] = SpecificationRelation::where('spec_value_id', $item)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();
                    //old code. $tempIds[$k] = DB::table('el_goods_spec_relation')->where('spec_value_id', $item)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();
                } else {
                    $specValueIds = SpecificationValue::where('color', $item)->select('id')->get()->pluck('id')->toArray();
                    $tempIds[$k] = SpecificationRelation::whereIn('spec_value_id', $specValueIds)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();
                    //old code.
                    /*$specValueIds = DB::table('el_goods_specs_value')->where('color', $item)->select('id')->get()->pluck('id')->toArray();
                    $tempIds[$k] = DB::table('el_goods_spec_relation')->whereIn('spec_value_id', $specValueIds)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();*/
                }
                ++$k;
            }

            $tmp_arr = [];
            if (count($tempIds) > 0) {
                foreach ($tempIds as $key => $val) {
                    if (0 == $key) {
                        $tmp_arr = $val;
                    } else {
                        $tmp_arr = array_intersect($tmp_arr, $val);
                    }
                }
            }

            $hasFlag = true;
        }

        if (!empty($tempIds)) {
            $specGoodIds = array_intersect($specGoodIds, $tmp_arr);
        }

        //4. get goods by attribute
        $attrGoodsIds = $specGoodIds;

        if ($attrArray = request('attr')) {
            if (!is_array($attrArray)) {
                $attrArray = explode(',', $attrArray);
            }

            foreach ($attrArray as $key => $item) {
                $attrarr[] = $item;
            }

            foreach ($attrarr as $k => $item) {
                $tempIds[$k] = SpecificationRelation::where('attribute_value_id', $item)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();
                //old code.
                /*$tempIds[$k] = DB::table('el_goods_attribute_relation')
                    ->where('attribute_value_id', $item)->select('goods_id')->distinct()->get()->pluck('goods_id')->toArray();*/
            }

            $tmp_arr = [];
            if (count($tempIds) > 0) {
                foreach ($tempIds as $key => $val) {
                    if (0 == $key) {
                        $tmp_arr = $val;
                    } else {
                        $tmp_arr = array_intersect($tmp_arr, $val);
                    }
                }
            } else {
                $tempIds = [];
            }
            $hasFlag = true;
        }

        if (!empty($tempIds)) {
            $attrGoodsIds = array_intersect($attrGoodsIds, $tmp_arr);
        }

        $goodIds = $this->getAttributeValueGoodsIds($attrGoodsIds, $hasFlag);

        //5. get goods list
        $goodsList = $this->goodsRepository->scopeQuery(function ($query) use ($goodIds, $hasFlag) {
            if (!empty($goodIds) or $hasFlag) {
                $query = $query->whereIn('id', $goodIds);
            }

            if (!empty(request('brand_id'))) {
                $query->where('brand_id', request('brand_id'));
            }

            if (!empty(request('price'))) {
                list($min, $max) = explode('-', request('price'));
                $query = $query->where('sell_price', '>=', $min);
                $query = $query->where('sell_price', '<=', $max);
            }

            if (!empty($keyword = request('keyword'))) {
                $query = $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')->orWhere('tags', 'like', '%' . $keyword . '%');
                });
            }

            return $query->where(['is_del' => 0, 'is_largess' => request('is_largess') ? request('is_largess') : 0]);
        })->orderBy($orderBy, $sort)->paginate(16);

        $filters = $this->generateFilterConditions();

        return $this->response()->paginator($goodsList, new GoodsTransformer('list'))->setMeta(['filter' => $filters]);
    }

    private function generateFilterConditions()
    {
        //如果是分类页面进入，则需要获取分类下所有商品的模型ID
        if ($categoryId = request('c_id')) {
            $categoryIds = $this->categoryRepository->getSubIdsById($categoryId);

            $goodsCategoryTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_category';
            $categoryGoodsIds = DB::table($goodsCategoryTable)->whereIn('category_id', $categoryIds)->select('goods_id')->distinct()->get()
                ->pluck('goods_id')->toArray();

            //$modelIds = $this->goodsRepository->findWhereIn('id', $categoryGoodsIds, ['model_id'])->pluck('model_id')->unique()->toArray();

            //$getAttrList = Attribute::ofModelIds($modelIds)->get();

            $attrRelations = AttributeRelation::whereIn('goods_id', $categoryGoodsIds)->select('attribute_value_id', 'attribute_id')->distinct()->get();
            $attrFilterID = $attrRelations->pluck('attribute_value_id')->unique()->toArray();
            $getAttrList = Attribute::whereIn('id', $attrRelations->pluck('attribute_id')->unique()->toArray());

            foreach ($getAttrList as $item) {
                $AttributeValue = $item->values->whereIn('id', $attrFilterID);
                foreach ($AttributeValue as $kitem) {
                    $attrArray[$item->name][$kitem->id] = $kitem->name;
                }
            }

            $attrArray = !isset($attrArray) ? [] : $attrArray;

            $attrFilters = ['attr' => ['keys' => array_keys($attrArray), 'values' => $attrArray]];

            $specArray = [];
            $getSpecList = Specification::with('values')->get();

            $SizeFilterID = SpecificationRelation::whereIn('goods_id', $categoryGoodsIds)->select('spec_value_id')->distinct()->get()->pluck('spec_value_id')->toArray();
            foreach ($getSpecList as $item) {
                $alias = 2 == $item->type ? 'color' : 'size';
                $specValue = $item->values->whereIn('id', $SizeFilterID);
                foreach ($specValue as $kitem) {
                    $itemName = $item->name . ':' . $alias;
                    if ($kitem->color) {
                        if (!isset($specArray[$itemName]) or
                            (isset($specArray[$itemName]) and !in_array($kitem->color, $specArray[$itemName]))
                        ) {
                            $specArray[$itemName][$kitem->color] = $kitem->color;
                        }
                    } else {
                        $specArray[$itemName][$kitem->id] = $kitem->name;
                    }
                }
            }

            $specArray = !isset($specArray) ? [] : $specArray;
            $specFilters = ['specs' => ['keys' => array_keys($specArray), 'values' => $specArray]];

            return array_merge($attrFilters, $specFilters);
        }

        return [];
    }

    private function getAttributeValueGoodsIds($goodIds, &$hasFlag)
    {
        $attrGoodsIds = $goodIds;
        if (request('attrValue') and $attrArray = array_unique(request('attrValue'))) {
            foreach ($attrArray as $key => $value) {
                $tempAttrIds[$value] = SpecificationRelation::where('attribute_value', 'like', '%' . $value . '%')
                    ->select('goods_id')
                    ->distinct()->get()->pluck('goods_id')->toArray();
            }

            if (!empty($tempAttrIds)) {
                $attrGoodsIds = empty($attrGoodsIds) ? array_first($tempAttrIds) : $attrGoodsIds;
                foreach ($tempAttrIds as $key => $value) {
                    $attrGoodsIds = array_intersect($attrGoodsIds, $value);
                }
            }

            $hasFlag = true;
        }

        return $attrGoodsIds;
    }

    public function show($id)
    {
        $goods = $this->goodsRepository->find($id);

        //获取优惠折扣
        $discounts = app(DiscountService::class)->getDiscountsByGoods($goods);
        if (!$discounts || count($discounts) == 0) {
            $result = null;
        } else {
            $result['discounts'] = collect_to_array($discounts->where('coupon_based', 0));
            $result['coupons'] = collect_to_array($discounts->where('coupon_based', 1));
        }

        return $this->response()->item($goods, new GoodsTransformer())
            ->setMeta(['attributes' => $goods->attr, 'discounts' => $result]);
    }

    public function getStock($id)
    {
        $goods = $this->goodsRepository->findOneById($id);

        if (!$goods) {
            return $this->failed('商品不存在.');
        }

        $specs = [];
        $stores = [];

        if ($products = $goods->products) {
            //生成库存信息
            $products->each(function ($item, $key) use (&$stores) {
                $specArray = $item->spec_ids;

                asort($specArray);

                $spec_id = implode('-', $specArray);
                $stores[$spec_id]['id'] = $item->id;
                $stores[$spec_id]['store'] = 1 == $item->is_show ? $item->store_nums : 0;
                $stores[$spec_id]['price'] = $item->sell_price;
                $stores[$spec_id]['sku'] = $item->sku;
                $stores[$spec_id]['ids'] = $item->spec_ids;
            });

            //生成规格信息
            $relations = SpecificationRelation::where('goods_id', $goods->id)
                ->with('spec', 'specValue')->orderBy('sort', 'asc')->get();

            $grouped = $relations->groupBy('spec_id');

            if ($grouped->count() > 2) {
                return $this->failed('数据错误，无法处理！');
            }

            $sort = 1;

            foreach ($grouped as $key => $item) {
                $specs[$sort]['id'] = $key;

                $relation = $item->first();

                $specs[$sort]['label_key'] = $relation->spec->name; //name is code name.

                $specs[$sort]['label'] = $relation->spec->display_name;

                //存储规格值
                $specs[$sort]['list'] = [];
                foreach ($item as $k => $value) {
                    $list = [];
                    $list['id'] = $value->specValue->id;
                    $list['value'] = $value->specValue->name;
                    $list['spec_img'] = $value->img;
                    $list['alias'] = $value->alias;
                    array_push($specs[$sort]['list'], $list);
                }
                ++$sort;
            }
        }

        return $this->success([
            'specs' => $specs,
            'stores' => $stores,
        ]);
    }

    public function shareView()
    {
        $id = request('id') ? request('id') : 0;

        $nick_name = request('nick_name') ? urldecode(request('nick_name')) : '';

        $avatar = request('avatar') ? request('avatar') : '';

        $filename = request('filename');

        $goods = $this->goodsRepository->find($id);

        return view('server::share.goods', compact('goods', 'avatar', 'nick_name', 'id', 'filename'));
    }

    public function shareImg($id)
    {
        $user = auth('api')->user();

        $page = request('page') ? request('page') : '';

        $miniProgram = Factory::miniProgram();

        $response = $miniProgram->app_code->getUnlimit($id, ['width' => 430, 'page' => $page]);

        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->save(storage_path('app/public/wxacode'));
        } else {
            return $this->failed('生成小程序码失败');
        }

        $nick_name = isset($user->nick_name) ? urlencode($user->nick_name) : '';

        $avatar = isset($user->avatar) ? $user->avatar : '';

        $filename = Storage::disk('public')->url('wxacode/' . $filename);

        $url = route('goods.share.view', compact('id', 'nick_name', 'avatar', 'filename'));

        $result = MiniProgramShareImg::generateShareImage($url, 'share_goods');

        return $this->success($result['url']);
    }
}
