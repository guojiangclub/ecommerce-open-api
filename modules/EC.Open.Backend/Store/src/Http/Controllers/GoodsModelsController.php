<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\AttributeValue;
use GuoJiangClub\EC\Open\Backend\Store\Model\Goods;
use GuoJiangClub\EC\Open\Backend\Store\Model\GoodsAttr;
use GuoJiangClub\EC\Open\Backend\Store\Model\Models;
use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Model\Attribute;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\ModelsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\SpecRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\AttributeRepository;
use DB;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class GoodsModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $modelsRepository;
    protected $attributeRepository;
    protected $specRepository;
    protected $goodsRepository;

    public function __construct(ModelsRepository $modelsRepository
        , AttributeRepository $attributeRepository
        , SpecRepository $specRepository
        , GoodsRepository $goodsRepository
    )
    {
        $this->modelsRepository = $modelsRepository;
        $this->attributeRepository = $attributeRepository;
        $this->specRepository = $specRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function index()
    {
        $models = $this->modelsRepository->all();

        return LaravelAdmin::content(function (Content $content) use ($models) {

            $content->header('模型列表');

            $content->breadcrumb(
                ['text' => '模型管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '模型列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模型管理']

            );

            $content->body(view('store-backend::model.index', compact('models')));
        });

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $spec = Spec::all();
        $attributes = Attribute::all();

        return LaravelAdmin::content(function (Content $content) use ($spec, $attributes) {

            $content->header('新增商品模型');

            $content->breadcrumb(
                ['text' => '模型管理', 'url' => 'store/models', 'no-pjax' => 1],
                ['text' => '新增商品模型', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模型管理']

            );

            $content->body(view('store-backend::model.create', compact('spec', 'attributes')));
        });

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $specIds = isset($input['spec_ids']) ? $input['spec_ids'] : [];
        $attrIds = isset($input['attr_ids']) ? $input['attr_ids'] : [];

        $base = ['name' => $input['name'], 'spec_ids' => $specIds];
        if (!$specIds) {
            unset($base['spec_ids']);
        }

        try {
            DB::beginTransaction();

            if (request('id')) {
                $model = Models::find(request('id'));
                $model->fill($base);
                $model->save();

            } else { //create
                $model = Models::create($base);
            }

            /*sync model_attribute relation*/
            $model->hasManyAttribute()->sync($attrIds);

            DB::commit();

            return response()->json(['status' => true
                , 'error_code' => 0
                , 'error' => ''
                , 'data' => '']);

        } catch (\Exception $exception) {
            DB::rollBack();

            \Log::info($exception->getMessage() . $exception->getTraceAsString());

            return response()->json(['status' => false
                , 'error_code' => 404
                , 'error' => '保存失败'
                , 'data' => '']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Models::find($id);
        $spec = Spec::all();
        $attributes = Attribute::all();

        $attrIds = [];
        foreach ($model->hasManyAttribute as $attribute) {
            array_push($attrIds, $attribute->pivot->attribute_id);
        }
        $goodsCount = Goods::where('model_id', $id)->count();

        return LaravelAdmin::content(function (Content $content) use ($model, $spec, $attributes, $attrIds, $goodsCount) {

            $content->header('编辑商品模型');

            $content->breadcrumb(
                ['text' => '模型管理', 'url' => 'store/models', 'no-pjax' => 1],
                ['text' => '编辑商品模型', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '模型管理']

            );

            $content->body(view('store-backend::model.edit', compact('model', 'spec', 'attributes', 'attrIds', 'goodsCount')));
        });
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $count = $this->goodsRepository->findByField('model_id', $id)->count();
        if (!$count) {
            $model = Models::find($id);

            foreach ($model->attribute as $item) {
                $item->values()->delete();
            }
            $model->attribute()->delete();
            $model->delete();
            return $this->ajaxJson(true);
        } else {
            return $this->ajaxJson(false);
        }

    }

    protected function getAttrValue($value)
    {
        $arr = explode(',', $value);
        $name = [];
        foreach ($arr as $val) {
            $name[]['name'] = $val;
        }

        return $name;
    }

    protected function getAttrValue2($value)
    {
        $name = [];
        foreach ($value['value'] as $val) {
            $name[]['name'] = $val;
        }

        return $name;
    }

    /**
     * 删除属性值
     * @param $id
     * @return mixed
     */
    public function deleteAttrValue($id)
    {
        $result = GoodsAttr::where('attribute_value_id', $id)->first();
        if ($result) {
            return $this->ajaxJson(false);
        } else {
            AttributeValue::find($id)->delete();
            return $this->ajaxJson(true);
        }
    }

    /**
     * 删除属性
     * @param $id
     */
    public function deleteAttr($id)
    {
        $result = GoodsAttr::where('attribute_id', $id)->first();
        if ($result) {
            return $this->ajaxJson(false);
        } else {
            Attribute::find($id)->delete();
            return $this->ajaxJson(true);
        }
    }

    /**
     * 关联规格状态变化检测
     * @param $id
     */
    public function checkSpec($id, $model_id)
    {
        $goodsTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods';
        $goodsSpecRelationTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_spec_relation';

        $result = DB::table($goodsTable)
            ->join($goodsSpecRelationTable, $goodsTable . '.id', '=', $goodsSpecRelationTable . '.goods_id')
            ->where([$goodsTable . '.model_id' => $model_id, $goodsSpecRelationTable . '.spec_id' => $id])
            ->get();

        if (count($result)) {
            return $this->ajaxJson(false);
        }

        return $this->ajaxJson(true);
    }

}
