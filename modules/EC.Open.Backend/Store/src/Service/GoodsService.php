<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Service;

use GuoJiangClub\EC\Open\Backend\Store\Model\AttributeValue;
use GuoJiangClub\EC\Open\Backend\Store\Model\Brand;
use GuoJiangClub\EC\Open\Backend\Store\Model\Category;
use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use GuoJiangClub\EC\Open\Backend\Store\Model\SpecsValue;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CategoryRepository;
use GuoJiangClub\EC\Open\Backend\Store\Model\Attribute;
use GuoJiangClub\EC\Open\Backend\Store\Model\Models;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ProductRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\SpecRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsCategoryRepository;

class GoodsService
{

    protected $goodsRepository;
    protected $modelsRepository;
    protected $specRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $goodsCategoryRepository;
    protected $attributeRepository;

    public function __construct()
    {
        $this->goodsRepository = app(GoodsRepository::class);
        $this->modelsRepository = app(Models::class);
        $this->specRepository = app(SpecRepository::class);
        $this->categoryRepository = app(CategoryRepository::class);
        $this->productRepository = app(ProductRepository::class);
        $this->goodsCategoryRepository = app(GoodsCategoryRepository::class);
        $this->attributeRepository = app(Attribute::class);
    }

    /**
     * 处理post过来的商品数据，进行数据分组
     *
     * @param $goodsAttrData    产品属性数据
     * @param $goodsUpdateData  产品基础数据
     * @param $paramData
     */
    public function handleGoodsUpdate($paramData)
    {
        $postData = [];
        $goodsAttrData = [];

        foreach ($paramData as $key => $val) {
            $postData[$key] = $val;
            //数据过滤分组
            if (strpos($key, 'attr_id_') !== false) {
                $goodsAttrData[ltrim($key, 'attr_id_')] = $val;
            } else {
                if ($key[0] != '_') {
                    $goodsUpdateData[$key] = $val;
                }
            }
        }


        $goodsUpdateData['store'] = array_sum($postData['_store_nums']);
        $goodsUpdateData['store_nums'] = $goodsUpdateData['store'];

        $goodsUpdateData['cost_price'] = isset($postData['_cost_price']) ? current($postData['_cost_price']) : 0;
        $goodsUpdateData['weight'] = isset($postData['_weight']) ? current($postData['_weight']) : 0;
        $goodsUpdateData['spec_array'] = isset($postData['spec_array']) ? $postData['spec_array'] : [];

        //内容处理
        $sync = $postData['sync'];
        if ($sync == 1) {
            $goodsUpdateData['contentpc'] = $goodsUpdateData['content'];
        } elseif ($sync == 2) {
            $goodsUpdateData['content'] = $goodsUpdateData['contentpc'];
        }

        //图片数据
        $imglist = isset($postData['_imglist']) ? $postData['_imglist'] : [];
        $is_default = isset($postData['_is_default']) ? $postData['_is_default'] : 0;
        $images = $this->handleImg($imglist, $is_default);
        $imgdata = $images[0];
        $goodsUpdateData['img'] = $images[1];

        //分类
        $catedata = isset($postData['_category_id']) ? $postData['_category_id'] : [];

        return $data = [$goodsUpdateData, $goodsAttrData, $postData, $imgdata, $catedata];
    }

    /**
     * 处理商品图片
     *
     * @param $imglist    原始图片数据
     * @param $isDefault  是否主图
     */
    public function handleImg($imglist, $isDefault)
    {
        $imgdata = [];
        $goodsDefaultImg = '';
        foreach ($imglist as $key => $val) {
            $default = 0;
            if ($isDefault === $key) {
                $goodsDefaultImg = $val['url'];
                $default = 1;
            }
            $imgdata[] = ['sort' => $val['sort'],
                'url' => $val['url'],
                'code' => $key,
                'is_default' => $default
            ];
        }

        //如未选择主图，默认排序最大的为主图
        if (!$isDefault && count($imglist) > 0) {
            $maxkey = '';
            foreach ($imgdata as $key => $val) {
                if (empty($maxval) || $val['sort'] > $maxval) {
                    $maxval = $val['sort'];
                    $maxkey = $key;
                }
            }
            $imgdata[$maxkey]['is_default'] = 1;
            $goodsDefaultImg = $imgdata[$maxkey]['url'];
        }

        return [$imgdata, $goodsDefaultImg];
    }

    /**
     * 商品属性处理
     *
     * @param $goodsAttrData
     * @param $model_id
     *
     * @return array
     */
    public function handleGoodsAttr($goodsAttrData, $model_id)
    {

        if (isset($goodsAttrData) && $goodsAttrData) {
            $attrValue = [];
            foreach ($goodsAttrData as $key => $val) {

                $attrValue[$key]['attribute_value'] = is_array($val) ? join(',', $val) : $val;
                $attrValue[$key]['model_id'] = $model_id;
            }

            return $attrValue;
        } else {
            return [];
        }
    }

    /**
     * 处理货品数据
     *
     * @param $postData
     * @param $goods_id
     *
     * @return array
     */
    public function handleProducts($postData)
    {
        //是否存在货品
        if (isset($postData['spec_array'])) {
            $productsData = [];
            // $specColor = $this->specRepository->getSpecColorList();

            //创建货品信息
            foreach ($postData['_goods_no'] as $key => $rs) {
//                $color = '';
//                foreach ($postData['spec_array'][$key] as $k => $v) {
//                    $specArr = json_decode($v);
//
//                    if($specArr->id == 2) {
//                        $colorKey = array_search($specArr->value, json_decode($specColor->extent));
//                        $color = json_decode($specColor->extent2)[$colorKey];
//                    }
//                }

                $productsData[] = [
                    'products_no' => $postData['_goods_no'][$key],
                    'store_nums' => $postData['_store_nums'][$key],
                    'market_price' => $postData['_market_price'][$key],
                    'sell_price' => $postData['_sell_price'][$key],
                    'cost_price' => $postData['_cost_price'][$key],
                    'weight' => $postData['_weight'][$key],
                    'sku' => $postData['_sku'][$key],
                    'is_show' => $postData['_is_show'][$key],
                    'spec_array' => "[" . join(',', $postData['spec_array'][$key]) . "]",
                    // 'color' => $color

                ];
                //dd( $postData['spec_array'][$key]);
            }

            return $productsData;
        } else {
            return [];
        }
    }

    /**
     * 添加商品时，根据选择的模型或商品ID获取预选规格选择项
     *
     * @param $model_id
     * @param $goods_id
     *
     * @return array
     */
    public function getGoodsSpecDataById($model_id, $goods_id)
    {
        $data = [];
        $specId = '';

        if ($goods_id) {
            $specData = $this->goodsRepository->find($goods_id, ['spec_array']);
            $data['goodsSpec'] = json_decode($specData['spec_array'], true);
            if ($data['goodsSpec']) {
                foreach ($data['goodsSpec'] as $item) {
                    $specId .= $item['id'] . ',';
                }
            }
        } else {
            if ($model_id) {
                $modelIdData = $this->modelsRepository->find($model_id, ['spec_ids']);
                $specId = $modelIdData['spec_ids'] ? $modelIdData['spec_ids'] : '';
            }
        }
        $data['specData'] = [];
        if ($specId) {
            $data['specData'] = $this->specRepository->findWhereIn('id', explode(',', trim($specId)));
        }

        // dd($data);
        return $data;
    }

    public function get_Category_list()
    {

        $cate_parent_list = $this->categoryRepository->orderBy('sort', 'asc')->findWhereIn('parent_id', [0]);
        $category_list = $this->categoryRepository->getLevelCategory();

        // return view('mobile.store.include.sidebar');
        return ['cate_parent_list' => $cate_parent_list, 'category_list' => $category_list];
    }

    /**
     * 保存商品时处理goods_spec中间表数据
     *
     * @param $specArray
     * @param $categoryId
     */
    public function handleGoodsSpecs($specArray, $categoryId)
    {
        //是否存在货品
        if (isset($specArray)) {
            foreach ($specArray as $key => $item) {
                foreach ($specArray[$key] as $k => $v) {
                    $specArray[$key][$k] = json_decode($v, true);
                    $new[$k][] = $specArray[$key][$k];
                }
            }

            $goodsSpecArr = multi_unique($new);
            $specStrArr = [];
            $cateIds = implode(",", $categoryId);
            foreach ($goodsSpecArr as $k => $v) {
                $specStr = '';
                $id = [];
                foreach ($v as $vkey => $value) {
                    $id[] = $value['id'];
                    $specStr = $value['value'] . "," . $specStr;
                }

                $specStrArr[$id[0]]['spec_value'] = rtrim($specStr, ",");
                $specStrArr[$id[0]]['category_id'] = $cateIds;
            }

            // dd($specStrArr);
            return $specStrArr;
        }
    }

    public function handleSearchSpecs($specArray, $categoryId, $goodsId)
    {

        //是否存在货品
        if (isset($specArray)) {
            $new = [];
            $i = 0;
            foreach ($specArray as $key => $item) {
                foreach ($specArray[$key] as $k => $v) {
                    //$specArray[$key][$k] = json_decode($v, true);

                    $spec_array = json_decode($v, true);
                    $new[$i]['goods_id'] = $goodsId;
                    $new[$i]['spec_id'] = $spec_array['id'];
                    $new[$i]['spec_value'] = $spec_array['value'];
                    $i++;
                }
            }

            $return = [];
            foreach ($new as $key => $v) {
                if (!in_array($v, $return)) {
                    $return[$key] = $v;
                }
            }

            $j = 0;
            $search = [];
            foreach ($return as $nkey => $nval) {
                foreach ($categoryId as $ckey => $cval) {

                    // $search[$j]['goods_id'] = $new[$nkey]['goods_id'];
                    $search[$j]['spec_id'] = $return[$nkey]['spec_id'];
                    $search[$j]['spec_value'] = $return[$nkey]['spec_value'];
                    $search[$j]['category_id'] = $cval;

                    if ($return[$nkey]['spec_id'] == 2) {
                        $specColor = $this->specRepository->getSpecColorList();

                        $colorKey = array_search($return[$nkey]['spec_value'], json_decode($specColor->value));
                        $color = json_decode($specColor->extent2)[$colorKey];
                        $search[$j]['color'] = $color;
                    }

                    $j++;
                }
            }

            return $search;
        }

        return [];
    }

    /** 通过商品指定属性（名称等）查找商品ID
     *
     * @param     $field string 指定属性
     * @param     $value string 属性值
     * @param int $is_del
     * @param int $limit
     *
     * @return array
     */

    public function nameGetGoods($field, $value)
    {

        $where = [];
        if (!empty($value)) {
            $where[$field] = ['like', '%' . $value . '%'];
        }

        $goods = $this->goodsRepository->getGoodsPaginated($where, 0);
        $goods_id = [];

        if ($goods->count() > 0) {
            foreach ($goods as $item) {
                $goods_id[] = $item->id;
            }
        }

        return $goods_id;
    }

    /**
     * NEW 2016-11-20
     * 处理初始化规格数据
     *
     * @param $specData
     * @param $goods_id
     */
    public function handleInitSpecData($specData, $goods_id = 0)
    {
        $specList = [];
        $skuData = [];
        $specsData = [];

        $filtered = $specData->filter(function ($value, $key) {
            return $value->id < 2;
        })->all();

        if (count($filtered) > 0) {
            $specData = $specData->sortByDesc('id')->values()->all();
        }

        $show_img = false;
        foreach ($specData as $item) {
            if ($item->type == 2) {
                $show_img = true;
                break;
            }
        }

        foreach ($specData as $key => $item) {

            $specList[$key]['id'] = $item->id;
            $specList[$key]['label'] = $item->name;
            $specList[$key]['type'] = $item->type;
            $specList[$key]['show_img'] = $show_img;

            $specValue = [];
            foreach ($item->specValue as $k => $val) {
                $specValue[$k]['id'] = $val->id;
                $specValue[$k]['value'] = $val->name;

                if ($val->spec_id == 2) {
                    $specValue[$k]['color'] = $val->rgb;
                }
            }

            $specList[$key]['list'] = $specValue;
        }

        //商品修改时
        if ($goods_id) {
            $goods = $this->goodsRepository->find($goods_id);

            foreach ($goods->hasManyProducts as $key => $item) {
                $store_nums = $item->store_nums;
                $id = $item->id;

                $skuData[$key]['id'] = $id;
                $skuData[$key]['sell_price'] = $item->sell_price;
                $skuData[$key]['store_nums'] = $store_nums;
                $skuData[$key]['sku'] = $item->sku;
                $skuData[$key]['is_show'] = $item->is_show;
                $skuData[$key]['specID'] = $item->spec_ids;
                $skuData[$key]['market_price'] = $item->market_price ? $item->market_price : $goods->market_price;
            }

            foreach ($goods->specValue as $val) {

                $spec = SpecsValue::find($val->pivot->spec_value_id);
                $specsData[$val->pivot->spec_value_id]['value'] = $spec->name;

                if ($val->pivot->spec_id == 2) {
                    $specsData[$val->pivot->spec_value_id]['color'] = '#' . $spec->rgb;
                }

                if ($val->pivot->alias) {
                    $specsData[$val->pivot->spec_value_id]['alias'] = $val->pivot->alias;
                }

                if ($val->pivot->img) {
                    $specsData[$val->pivot->spec_value_id]['image'] = $val->pivot->img;
                }

                $specsData[$val->pivot->spec_value_id]['index'] = $val->pivot->sort;
            }
        }

        return ['specs' => $specList, 'skus' => ['skuData' => $skuData, 'specData' => $specsData]];
    }

    /**
     * 处理post数据
     *
     * @param $paramData
     *
     * @return array
     */
    public function handleGoodsData($paramData)
    {
        $postData = [];
        $goodsAttrData = [];
        $goodsSpecData = [];
        $goodsSpecIds = [];
        $goodsSpecAlias = [];     //规格自定义数据-别名
        $goodsSpecImg = [];     //规格自定义数据-图片
        $goodsSpecIndex = [];   //规格自定义数据-排序
        $goodsPoint = []; //积分数据
        $catedata = []; //分类数据

        foreach ($paramData as $key => $val) {
            $postData[$key] = $val;
            //数据过滤分组
            if (strpos($key, 'attr_id_') !== false) {
                $goodsAttrData[ltrim($key, 'attr_id_')] = $val; //属性数据

            } else {
                if (strpos($key, 'spec_id_') !== false AND isset($paramData['_spec'])) {

                    $goodsSpecIds[ltrim($key, 'spec_id_')] = $val; //规格数据

                } else {
                    if (strpos($key, 'category_id') !== false) {

                        $catedata = $val; //分类数据

                    } else {
                        if (strpos($key, 'spec_alias') !== false) {

                            $goodsSpecAlias = $val;     //规格自定义数据-别名

                        } else {
                            if (strpos($key, 'spec_img') !== false) {

                                $goodsSpecImg = $val;     //规格自定义数据-图片

                            } else {
                                if (strpos($key, 'spec_index') !== false) {

                                    $goodsSpecIndex = $val;     //规格自定义数据-规格排序

                                } else {
                                    if (strpos($key, 'point_') !== false) {

                                        $goodsPoint[substr($key, 6)] = $val;     //积分数据

                                    } else {
                                        if ($key[0] != '_') {
                                            $goodsUpdateData[$key] = $val;  //基础数据

                                        } else {
                                            $goodsSpecData[$key] = $val;    //货品规格数据
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //内容处理
        $sync = $postData['sync'];
        if ($sync == 1 AND isset($goodsUpdateData['content'])) {
            $goodsUpdateData['contentpc'] = $goodsUpdateData['content'];
        } elseif ($sync == 2 AND isset($goodsUpdateData['contentpc'])) {
            $goodsUpdateData['content'] = $goodsUpdateData['contentpc'];
        }

        //图片数据
        $imglist = isset($postData['_imglist']) ? $postData['_imglist'] : [];
        $is_default = isset($postData['_is_default']) ? $postData['_is_default'] : 0;
        $images = $this->handleImg($imglist, $is_default);
        $imgdata = $images[0];
        $goodsUpdateData['img'] = $images[1];

        //属性
        $goodsAttrData = $this->attrArray($goodsAttrData, $goodsUpdateData['model_id']);

        //产品规格关系
        $goodsSpecIdsRelation = $this->specIdsArray($goodsSpecIds, $goodsSpecAlias, $goodsSpecImg, $goodsSpecIndex);

        //产品数据
        $goodsSpecData = isset($goodsSpecData['_spec']) ? $goodsSpecData['_spec'] : [];

        foreach ($goodsSpecData as $item) {
            if (!$item['sku']) {
                return ['status' => false, 'msg' => 'SKU不能为空'];
            }
        }

        //库存
        $goodsUpdateData['store_nums'] = count($goodsSpecData) ? $this->sumStore($goodsSpecData, 'store_nums') : $goodsUpdateData['store_nums'];

        $goodsUpdateData['min_price'] = $goodsUpdateData['sell_price'];
        $goodsUpdateData['max_price'] = $goodsUpdateData['sell_price'];
        $goodsUpdateData['min_market_price'] = $goodsUpdateData['market_price'];

        return $data = [$goodsUpdateData, $goodsAttrData, $postData, $imgdata, $catedata, $goodsSpecData, $goodsSpecIdsRelation, $goodsPoint];
    }

    /**
     * 库存总合
     *
     * @param $array
     * @param $column
     *
     * @return int
     */
    protected function sumStore($array, $column)
    {
        $sum = 0;
        if (is_array($array)) {
            foreach ($array as $key => $val) {
                $sum += (int)$val[$column];
            }
        }

        return $sum;
    }

    /**
     * 处理商品属性
     *
     * @param $attr     属性ID
     * @param $model_id 模型ID
     */
    protected function attrArray($attr, $model_id)
    {
        $data = [];
        foreach ($attr as $key => $val) {
//            if($val){
//                //$value = explode('::', $val);
//                $data[$key]['model_id'] = $model_id;
//                $data[$key]['attribute_id'] = $key;
////                $data[$key]['attribute_value'] = $value[1];
//                $data[$key]['attribute_value_id'] = $val;
//            }

            if ($val) {
                $data[$key]['model_id'] = $model_id;

                $attrData = $this->attributeRepository->find($key);
                if ($attrData->type == 1) {
                    $data[$key]['attribute_value_id'] = $val;
                    if ($attribute = AttributeValue::find($val)) {
                        $data[$key]['attribute_value'] = $attribute->name;
                    }
                } else {
                    $data[$key]['attribute_value'] = $val;
                }
            }
        }

        return $data;
    }

    /**
     * 处理产品规格关系表数据
     *
     * @param $specArr
     */
    public function specIdsArray($specArr, $goodsSpecAlias, $goodsSpecImg, $goodsSpecIndex)
    {
        $specIds = [];
        $i = 0;
        foreach ($specArr as $key => $val) {
            foreach ($val as $item) {

                $specIds[$item]['spec_id'] = $key;
                $specIds[$item]['alias'] = isset($goodsSpecAlias[$item]) ? $goodsSpecAlias[$item] : '';
                $specIds[$item]['img'] = isset($goodsSpecImg[$item]) ? $goodsSpecImg[$item] : '';
                $specIds[$item]['sort'] = isset($goodsSpecIndex[$item]) ? $goodsSpecIndex[$item] : '';
            }
            $i++;
        }

        return $specIds;
    }

    /**
     * 修改商品，货品数据再加工
     *
     * @param $data    货品数据
     * @param $product 货品集合
     */
    public function deepProductData($data, $product)
    {
        $editData = [];
        $newData = [];
        $editPid = [];
        $delData = [];

        if (count($data)) {
            foreach ($data as $key => $val) {
                if ($val['id']) {
                    $editData[$key] = $val;
                    $editPid[] = $val['id'];
                } else {
                    $newData[$key] = $val;
                }
            }

            $delData = $product->reject(function ($item) use ($editPid) {
                return in_array($item->id, $editPid);
            })->all();
        }

        return ['editData' => $editData, 'newData' => $newData, 'delData' => $delData];
    }

    /**
     * 通过产品sku获取商品ID
     *
     * @param $sku
     *
     * @return mixed
     */
    public function skuGetGoodsIds($sku)
    {
        $ids = $this->productRepository->getProductPaginated(['sku' => ['like', '%' . $sku . '%']], 0)->pluck('goods_id')->toArray();
        if (!empty($ids)) {
            return $ids;
        }

        return false;
    }

    /**
     * 根据SKU 市场价和销售价获取商品ID
     *
     * @param $filed
     *
     * @return bool
     */
    public function skuPriceGoodsIds($filed)
    {
        $column = $filed == 'sku_sell_price' ? 'sell_price' : 'market_price';

        $ids = $this->productRepository->getProductPaginated([], 0, $column)->pluck('goods_id')->toArray();
        if (count($ids) > 0) {
            return $ids;
        }

        return [];
    }

    public function categoryGetGoodsIds($category)
    {
        $categoryIds = Category::where('name', 'like', '%' . $category . '%')->pluck('id')->toArray();
        $goods = $this->goodsRepository->with('categories')->all();

        return $goods->filter(function ($value, $key) use ($categoryIds) {
            foreach ($value->categories as $category) {
                if (in_array($category->id, $categoryIds)) {
                    return true;
                }
            }

            return false;
        })->pluck('id')->toArray();
    }

    /**
     * 检测SKU唯一性
     *
     * @param $goods_id
     *
     * @return bool
     */
    public function checkUniqueSku($goods_id)
    {
        $products = $this->productRepository->findWhere(['goods_id' => $goods_id]);
        $unique = $products->unique('sku')->all();

        $filtered = $products->filter(function ($value, $key) {
            return $this->productRepository->findWhere(['sku' => $value->sku, ['goods_id', '<>', $value->goods_id]])->count() > 0;
        })->all();

        if ((count($products) <> count($unique)) OR count($filtered) > 0) {
            return false;
        }

        return true;
    }

    /**
     * 新增商品自动同步信息到分销
     *
     * @param $goods_id
     */
    public function syncAgentGoods($goods_id)
    {
        if (settings('distribution_status') AND settings('distribution_goods_status')) {
            if (!AgentGoods::where('goods_id', $goods_id)->first()) {
                $rate = settings('distribution_goods_rate');
                AgentGoods::create([
                    'goods_id' => $goods_id,
                    'activity' => 1,
                    'rate' => $rate,
                    'rate_organ' => $rate,
                    'rate_shop' => $rate
                ]);
            }
        }
    }
    

    public function checkSellPrice($data)
    {
        if (settings('goods_price_protection_enabled') AND count($data) > 0) {
            foreach ($data as $item) {
                if ($item['market_price'] * (settings('goods_price_protection_discount_percentage') / 100) > $item['sell_price']) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 检测规格，模型，分类等信息是否已创建
     */
    public function checkGoodsCreateBefore()
    {
        $data['spec'] = Spec::all()->count();
        $data['model'] = Models::all()->count();
        $data['brand'] = Brand::all()->count();
        $data['category'] = Category::all()->count();

        $flag = null;
        foreach ($data as $key => $item) {
            if (!$item) {
                $flag = $key;
                break;
            }
        }
        if (!$flag) return ['status' => true];

        switch ($flag) {
            case 'spec':
                $url = route('admin.goods.spec.create');
                break;
            case 'model':
                $url = route('admin.goods.model.create');
                break;
            case 'brand':
                $url = route('brand.create');
                break;
            default:
                $url = route('admin.category.create');
        }

        return ['status' => false, 'url' => $url];

    }
}
