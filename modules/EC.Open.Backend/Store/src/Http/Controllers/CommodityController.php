<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\Goods;
use GuoJiangClub\EC\Open\Backend\Store\Model\Models;
use GuoJiangClub\EC\Open\Backend\Store\Model\Product;
use GuoJiangClub\EC\Open\Backend\Store\Model\GoodsPhoto;
use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ModelsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\SpecRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\AttributeRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\BrandRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CategoryRepository;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ProductRepository;
use GuoJiangClub\EC\Open\Backend\Store\Service\GoodsService;
use Response;
use Route;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Validator;

class CommodityController extends Controller
{
    protected $modelsRepository;
    protected $specRepository;
    protected $attributeRepository;
    protected $brandRepository;
    protected $goodsRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $goodsService;
    protected $cache;

    protected static $errorSku;
    protected static $successSkuNum;
    protected static $goodsID;

    public function __construct(ModelsRepository $modelsRepository
        , SpecRepository $specRepository
        , AttributeRepository $attributeRepository
        , BrandRepository $brandRepository
        , GoodsRepository $goodsRepository
        , ProductRepository $productRepository
        , CategoryRepository $categoryRepository
        , GoodsService $goodsService
    )
    {

        $this->modelsRepository = $modelsRepository;
        $this->specRepository = $specRepository;
        $this->attributeRepository = $attributeRepository;
        $this->brandRepository = $brandRepository;
        $this->goodsRepository = $goodsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->goodsService = $goodsService;
        $this->cache = cache();
    }

    public function index()
    {
        $where = [];
        $where_ = [];

        $view = !empty(request('view')) ? request('view') : 0;
        $where['is_del'] = ['=', $view];


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

        if (!empty(request('price_begin')) AND !empty(request('price_end')) AND request('price') != 'sku_market_price' AND request('price') != 'sku_sell_price') {
            $where[request('price')] = ['>=', request('price_begin')];
            $where_[request('price')] = ['<=', request('price_end')];
        }

        if (!empty(request('price_begin')) AND request('price') != 'sku_market_price' AND request('price') != 'sku_sell_price') {
            $where_[request('price')] = ['>=', request('price_begin')];
        }

        if (!empty(request('price_end')) AND request('price') != 'sku_market_price' AND request('price') != 'sku_sell_price') {
            $where_[request('price')] = ['<=', request('price_end')];
        }

        $goods_ids = [];
        if (request('field') == 'sku' && !empty(request('value'))) {
            $goods_ids = $this->goodsService->skuGetGoodsIds(request('value'));
        }
        if (request('field') == 'category' && !empty(request('value'))) {
            $goods_ids = $this->goodsService->categoryGetGoodsIds(request('value'));
        }

        /*对SKU市场价、销售价搜索*/
        if (request('price') == 'sku_market_price' OR request('price') == 'sku_sell_price') {
            $goods_ids_by_sku_price = $this->goodsService->skuPriceGoodsIds(request('price'));
            if (count($goods_ids_by_sku_price)) {
                $goods_ids = array_merge($goods_ids, $goods_ids_by_sku_price);
            }
        }

        $goods = $this->goodsRepository->getGoodsPaginated($where, $where_, $goods_ids, 50);

        if (request('view') == 2) {
            $whereCount = $where;
            $whereCount['is_del'] = 2;
            $offCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
            $whereCount['is_del'] = 0;
            $allCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
            $whereCount['is_del'] = 1;
            $delCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
        } else {
            $whereCount = $where;
            $whereCount['is_del'] = 0;
            $allCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
            $whereCount['is_del'] = 2;
            $offCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
            $whereCount['is_del'] = 1;
            $delCount = $this->goodsRepository->getGoodsPaginated($whereCount, $where_, $goods_ids, 0)->count();
        }

        return LaravelAdmin::content(function (Content $content) use ($goods, $allCount, $offCount, $delCount) {

            $content->header('商品列表');

            $content->breadcrumb(
                ['text' => '商品管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '商品列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品列表']
            );

            $content->body(view('store-backend::commodity.index', compact('goods', 'allCount', 'offCount', 'delCount')));
        });
    }

    public function createBefore()
    {
        $before = $this->goodsService->checkGoodsCreateBefore();
        if (!$before['status']) {
            $url = $before['url'];
        } else {
            return redirect(route('admin.goods.create'));
        }

        return LaravelAdmin::content(function (Content $content) use ($url) {

            $content->header('新建商品');

            $content->breadcrumb(
                ['text' => '商品管理', 'url' => 'store/goods', 'no-pjax' => 1],
                ['text' => '新建商品', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品列表']

            );

            $content->body(view('store-backend::commodity.create_before', compact('url')));
        });
    }

    public function create()
    {
        $before = $this->goodsService->checkGoodsCreateBefore();
        if (!$before['status']) return redirect(route('admin.goods.createBefore'));

        $models = $this->modelsRepository->all();
        $brands = $this->brandRepository->all();
        $point_rule = settings('point_goods_enabled');
        $point_rule_value = settings('point_goods_ratio');
        if (!$point_rule OR !$point_rule_value) {
            $point_rule_value = 0;
        }

        return LaravelAdmin::content(function (Content $content) use ($models, $brands, $point_rule, $point_rule_value) {

            $content->header('新建商品');

            $content->breadcrumb(
                ['text' => '商品管理', 'url' => 'store/goods', 'no-pjax' => 1],
                ['text' => '新建商品', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品列表']

            );

            $content->body(view('store-backend::commodity.create', compact('models', 'brands', 'point_rule', 'point_rule_value')));
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateForm($request->input('id'));

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return response()->json(['status' => false
                , 'error_code' => 0
                , 'error' => $show_warning
                , 'error_key' => key($warnings->toArray())
            ]);
        }

        $input = $request->except('_token', 'file', 'specJson', 'upload_image');

        $data = $this->goodsService->handleGoodsData($input);   //将商品数据进行分组处理

        if (isset($data['status']) AND !$data['status']) {
            return response()->json(['status' => false
                    , 'error_code' => 0
                    , 'error' => $data['msg']
                    , 'data' => ''
                    , 'error_key' => 'sku']
            );
        }

        $goodsData = $data[0];      //商品基础数据
        $goodsAttributeData = $data[1];     //商品属性数据

        if (!$this->goodsService->checkSellPrice($data['5'])) {
            return response()->json(['status' => false
                , 'error_code' => 0
                , 'error' => '有SKU销售价低于价格保护设置'
                , 'data' => ''
                , 'error_key' => 'sku']);
        }

        try {
            DB::beginTransaction();

            if (request('id'))   //更新操作
            {
                $goods = $this->goodsRepository->update($goodsData, request('id'));

                //商品属性数据处理
                $goods->hasManyAttribute()->sync($goodsAttributeData);

                //商品货品处理
                $specData = $data[5];

                if (count($specData)) {
                    $productsData = $this->goodsService->deepProductData($data[5], $goods->hasManyProducts);

                    $goods->hasManyProducts()->createMany($productsData['newData']);

                    foreach ($productsData['editData'] as $item) {
                        $product = Product::find($item['id']);
                        if ($product->store_nums == 0 && $item['store_nums'] > 0) {
                            $template_settings = settings('wechat_message_arrival_of_goods');
                            if (isset($template_settings['status']) && $template_settings['status']) {
                                event('goods.stock.changed', [request('id'), $item['sku']]);
                            }
                        }
                        $product->fill($item);
                        $product->save();

                        $goods->store_nums = $goods->store_nums + $item['store_nums'] - $product->store_nums;
                        $goods->save();
                    }

                    foreach ($productsData['delData'] as $item) {
                        Product::find($item->id)->delete();
                    }
                } else {
                    Product::where('goods_id', $goods->id)->delete();
                    $goods->specValue()->detach();
                }

                GoodsPhoto::where('goods_id', request('id'))->delete();
                $goods->GoodsPhotos()->createMany($data[3]);

                $goods->categories()->sync($data[4]);
            } else {
                //商品基础数据处理
                $goods = $this->goodsRepository->create($goodsData);

                //商品属性数据处理
                $goods->hasManyAttribute()->sync($goodsAttributeData);

                //商品货品处理
                $goods->hasManyProducts()->createMany($data[5]);

                //商品图片
                $goods->GoodsPhotos()->createMany($data[3]);

                //分类
                $goods->categories()->sync($data[4]);

                $this->goodsService->syncAgentGoods($goods->id);
            }
            //规格中间表数据
            if (isset($input['_spec'])) {
                $goods->specValue()->sync($data[6]);
            }

            if (!$this->goodsService->checkUniqueSku($goods->id)) {
                return response()->json(['status' => false
                    , 'error_code' => 0
                    , 'error' => '存在重复的SKU值'
                    , 'data' => ''
                    , 'error_key' => 'sku']);
            }

            DB::commit();

            return response()->json(['status' => true
                , 'error_code' => 0
                , 'error' => ''
                , 'data' => $input]);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }

    protected function validateForm($id = 0)
    {
        $rules = [
            'name' => 'required',
            'brand_id' => 'required',
            'model_id' => 'required',
            'store_nums' => 'required | integer',
            'market_price' => 'required',
            'sell_price' => 'required',
            'category_id' => 'required',
            '_imglist' => 'required',
            'sort' => 'integer',
        ];

        $message = [
            "required" => ":attribute 不能为空",
            "integer" => ":attribute 必须是整数",
            "unique" => ":attribute 已经存在",
        ];

        $attributes = [
            "name" => '商品名称',
            "brand_id" => '品牌选择',
            "model_id" => '模型选择',
            'store_nums' => '商品数量',
            '_imglist' => '商品图片',
            'market_price' => '市场价',
            'sell_price' => '销售价',
            'goods_no' => '商品编号',
            'category_id' => '分类选择',
            '_spec.*.market_price' => 'SKU市场价',
            '_spec.*.sell_price' => 'SKU销售价',
            '_spec.*.store_nums' => 'SKU库存',
            '_spec' => '规格选择',
            'sort' => '排序',
        ];

        $validator = Validator::make(request()->all(), $rules, $message, $attributes);

        $validator->sometimes('goods_no', "unique:" . config('ibrand.app.database.prefix', 'ibrand_') . "goods,goods_no,$id", function ($input) {
            return $input->id;
        });

        $validator->sometimes('goods_no', "unique:" . config('ibrand.app.database.prefix', 'ibrand_') . "goods,goods_no", function ($input) {
            return !$input->id;
        });

        $validator->sometimes(['_spec.*.market_price', '_spec.*.sell_price', '_spec.*.store_nums'], 'required', function ($input) {
            return isset($input->_spec) AND count($input->_spec) > 0;
        });

        return $validator;
    }

    public function edit($id)
    {
        if (!($goods_info = Goods::find($id))) {
            return \Redirect::route('admin.goods.index');
        }

        $redirect_url = request('redirect_url');

        $cateIds = $goods_info->categories->pluck('id')->all();
        $cateNames = $goods_info->categories->all();

        $product = $goods_info->hasManyProducts()->get();

        $brands = $this->brandRepository->all();

        $models = $this->modelsRepository->all();

        $categories = $this->categoryRepository->getOneLevelCategory();
        $categoriesLevelTwo = [];
        foreach ($categories as $category) {
            if (in_array($category->id, $cateIds) && $category->parent_id == 0) {
                $categoriesLevelTwo[] = $this->categoryRepository->getOneLevelCategory($category->id);
            }
        }

        $currAttribute = $this->goodsRepository->getAttrArray($id);     //获取商品属性数据

        //获取所选模型下规格数据
        $model = Models::find($goods_info->model_id);
        $spec = Spec::whereIn('id', $model->spec_ids)->get();

        $specData = $this->goodsService->handleInitSpecData($spec, $id);

        $attrArray = $model->hasManyAttribute;


        return LaravelAdmin::content(function (Content $content) use ($cateNames, $goods_info, $product, $categories, $brands, $models, $attrArray, $currAttribute, $specData, $cateIds, $redirect_url, $categoriesLevelTwo) {

            $content->header('编辑商品');

            $content->breadcrumb(
                ['text' => '商品管理', 'url' => 'store/goods', 'no-pjax' => 1],
                ['text' => '编辑商品', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品列表']

            );

            $content->body(view('store-backend::commodity.edit', compact('cateNames', 'goods_info', 'product', 'categories', 'brands', 'models', 'attrArray', 'currAttribute', 'specData', 'cateIds', 'redirect_url', 'categoriesLevelTwo')));
        });
    }

    /**
     * 删除商品检测参与活动状态
     *
     * @return mixed
     */
    public function checkPromotionStatus()
    {
        $id = request('id');
        $status = $this->goodsService->checkPromotionStatusByGoodsID($id);
        if ($status) {
            return $this->ajaxJson();
        }

        return $this->ajaxJson(false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $goods = $this->goodsRepository->find($id);
        try {
            DB::beginTransaction();
            $goods->hasManyAttribute()->detach();       //删除属性
            Product::where('goods_id', $id)->delete();    //删除货品
            GoodsPhoto::where('goods_id', $id)->delete(); //删除图片
            $this->goodsRepository->delete($id);        //删除产品
            DB::commit();

            return $this->ajaxJson();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '删除失败');
        }
    }

    /**
     * 删除产品
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $goods = $this->goodsRepository->find($id);
        $goods->is_del = 1;
        $goods->save();

        return $this->ajaxJson();
    }

    /**
     * 恢复商品
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $goods = $this->goodsRepository->find($id);
        $goods->is_del = 0;
        $goods->save();

        return $this->ajaxJson();
    }


    /**
     * 创建商品获取分类数据
     * @return mixed
     */
    public function getCategoryByGroupID()
    {
        if (request()->has('type-click-category-button')) {
            $categories = $this->categoryRepository->getOneLevelCategory(request('parentId'));

            return response()->json($categories);
        } else {
            $categories = $this->categoryRepository->getOneLevelCategory();
            return view('store-backend::commodity.includes.category-item', compact('categories'));
        }
    }

    /**
     * 根据选择的模型获取模型属性数据
     */
    public function getAttribute()
    {
        if (request('model_id')) {
            $model = Models::find(request('model_id'));

            $attribute = $model->hasManyAttribute;

            return view('store-backend::commodity.includes.attribute_template', compact('attribute'));
        }
    }

    /**
     * 根据选择的模型获取规格数据
     */
    public function getSpecsData()
    {
        if (request('model_id')) {
            $model = Models::find(request('model_id'));
            $spec = Spec::whereIn('id', $model->spec_ids)->get();

            $specList = $this->goodsService->handleInitSpecData($spec);

            return $this->ajaxJson(true, [
                'specs' => $specList,
            ]);
        }

        return $this->ajaxJson(false);
    }

    /**
     * 批量导入库存
     * @return Content
     */
    public function uploadStock()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('批量导入商品库存');

            $content->breadcrumb(
                ['text' => '商品管理', 'url' => 'store/goods', 'no-pjax' => 1],
                ['text' => '批量导入商品库存', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商品列表']

            );

            $content->body(view('store-backend::commodity.upload_stock'));
        });
    }

    public function doUploadStock(Request $request)
    {
        $filename = 'public' . $request['upload_excel'];

        Excel::load($filename, function ($reader) {
            $error_list = [];
            $goodsID = [];
            self::$errorSku = $error_list;
            self::$goodsID = $goodsID;
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();

            if (count($results) > 1) {
                $i = 0;
                foreach ($results as $key => $value) {
                    if ($key != 0) {
                        $prouct_list = null;
                        if (!is_null($value[0]) && !is_null($value[1])) {
                            $prouct_list = $this->productRepository->findWhere(['sku' => $value[0]])->first();
                        }
                        if ($prouct_list) {
                            if ($res = $this->productRepository->updateOrCreate(['sku' => $value[0]], ['store_nums' => $value[1]])) {
                                if (!in_array($res->goods_id, $goodsID)) {
                                    $goodsID[] = $res->goods_id;
                                }
                                $i++;
                                self::$successSkuNum = $i;
                                self::$goodsID = $goodsID;
                            }
                        } elseif ($goods = Goods::where('goods_no', $value[0])->first()) {

                            $goods->store_nums = $value[1];
                            $goods->save();

                            $i++;
                            self::$successSkuNum = $i;
                            self::$goodsID = $goodsID;
                        } else {
                            $error_list[] = $value[0];
                            self::$errorSku = $error_list;
                        }
                    }
                }
            }
        });

        if (count(self::$goodsID) > 0) {
            foreach (self::$goodsID as $item) {
                $store_nums = $this->productRepository->findWhere(['goods_id' => $item])->pluck('store_nums')->sum();
                $this->goodsRepository->update(['store_nums' => $store_nums], $item);
            }
        }

        if (count(self::$errorSku)) {
            $data['sku'] = '未导入成功的SKU:' . implode(' ', self::$errorSku);
        } else {
            $data['sku'] = '';
        }
        $data['num'] = empty(self::$successSkuNum) ? '0' : self::$successSkuNum;

        return response()->json([
            'status' => true
            , 'error_code' => 0
            , 'error' => 0
            , 'data' => $data,
        ]);
    }


    /**
     * 获取导出数据
     *
     * @return mixed
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 200;

        $goods = $this->productRepository->getExcelGoods($limit);

        $lastPage = $goods['lastPage'];
        $goods = $goods['products'];
        $type = request('type');

        if ($page == 1) {
            session(['export_goods_cache' => generate_export_cache_name('export_goods_cache_')]);
        }
        $cacheName = session('export_goods_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $goods), 300);
        } else {
            $this->cache->put($cacheName, $goods, 300);
        }

        if ($page == $lastPage) {
            $title = ['商品ID', 'SKU', '商品编号', '商品名称', '类型', 'SPU吊牌价', 'SPU销售价', 'SKU市场价', 'SKU销售价', '上架', '库存', '标签', '尺码', '颜色', '自定义颜色', '分类'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'goods_data_']);
        } else {
            $url_bit = route('admin.goods.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));

            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
        }
    }

    /**
     * 批量修改商品标题
     */
    public function operationTitle()
    {
        $ids = implode(',', request('ids'));
        $num = count(request('ids'));

        return view('store-backend::commodity.includes.operation_title', compact('ids', 'num'));
    }

    public function saveTitle()
    {
        $ids = explode(',', request('ids'));

        if (!$type = request('type')) {
            return $this->ajaxJson(false, [], 404, '请选择修改项');
        }

        $goods = Goods::whereIn('id', $ids)->get();

        try {
            DB::beginTransaction();
            foreach ($goods as $item) {
                if ($type == 'add') {   //增加前后缀
                    $prefix = request('prefix') ? request('prefix') : '';
                    $suffix = request('suffix') ? request('suffix') : '';
                    $item->name = $prefix . $item->name . $suffix;
                } elseif ($type == 'all') { //完整替换标题
                    if (!$title = request('title')) {
                        return $this->ajaxJson(false, [], 404, '标题不能为空');
                    }
                    $item->name = $title;
                } else {      //查找替换
                    if (!$find = request('find')) {
                        return $this->ajaxJson(false, [], 404, '查找标题不能为空');
                    }
                    $replace = request('replace') ? request('replace') : '';
                    $item->name = str_replace($find, $replace, $item->name);
                }

                $item->save();
            }
            DB::commit();

            return $this->ajaxJson(true);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '修改失败');
        }
    }

    /**
     * 批量添加商品标签
     */
    public function operationTags()
    {
        $ids = implode(',', request('ids'));

        return view('store-backend::commodity.includes.operation_tags', compact('ids'));
    }

    public function saveTags()
    {
        $ids = explode(',', request('ids'));

        if (!$tags = request('tags')) {
            return $this->ajaxJson(false, [], 404, '请输入标签');
        }

        $goods = Goods::whereIn('id', $ids)->get();

        try {
            DB::beginTransaction();
            foreach ($goods as $item) {
                if (!$item->tags) {
                    $item->tags = $tags;
                } else {
                    $item->tags = $item->tags . ',' . $tags;
                }
                $item->save();
            }
            DB::commit();

            return $this->ajaxJson(true);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '添加失败');
        }
    }

    /**
     * 批量上下架商品
     *
     * @return mixed
     */
    public function saveIsDel()
    {
        $goodsIds = request('gid');
        $status = request('lineGoods');
        $error_list = [];

        foreach ($goodsIds as $id) {
            $goods = $this->goodsRepository->find($id);
            if ($status == 2) { //如果是下架操作
                $goods->is_del = 2;
            } elseif ($status == 1) {  //删除
                $goods->is_del = 1;
            } else {
                $goods->is_del = 0;
            }
            $goods->save();
        }

        return $this->ajaxJson(true, ['error_list' => $error_list]);
    }

    /**
     * 更新排序
     * @param Request $request
     * @return mixed
     */
    public function updateSort(Request $request)
    {
        $input = $request->except('_token', 'file');

        $this->goodsRepository->update(['sort' => $input['value']], $input['pk']);

        return $this->ajaxJson();
    }
}
