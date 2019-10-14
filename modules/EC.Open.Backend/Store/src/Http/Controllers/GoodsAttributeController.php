<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\Attribute;
use GuoJiangClub\EC\Open\Backend\Store\Model\AttributeValue;
use GuoJiangClub\EC\Open\Backend\Store\Model\Models;
use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use GuoJiangClub\EC\Open\Backend\Store\Model\SpecsValue;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class GoodsAttributeController extends Controller
{

    public function index()
    {
        $attributes = Attribute::all();

        return LaravelAdmin::content(function (Content $content) use ($attributes) {

            $content->header('商品参数列表');

            $content->breadcrumb(
                ['text' => '参数管理', 'url' => 'store/attribute', 'no-pjax' => 1],
                ['text' => '商品参数列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '参数管理']

            );

            $content->body(view('store-backend::attribute.index', compact('attributes')));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return LaravelAdmin::content(function (Content $content) {

            $content->header('新建商品参数');

            $content->breadcrumb(
                ['text' => '参数管理', 'url' => 'store/attribute', 'no-pjax' => 1],
                ['text' => '新建商品参数', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '参数管理']

            );

            $content->body(view('store-backend::attribute.create'));
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
        $input = $request->except('id', '_token', '_attr_value_id', '_attr_value', 'value');
        $updateID = $request->input('_attr_value_id');
        $updateValue = $request->input('_attr_value');
        $selectValue = $request->input('value');

        if (!request('is_search')) {
            $input['is_search'] = 0;
        }

        if (!request('is_chart')) {
            $input['is_chart'] = 0;
        }

        try {
            DB::beginTransaction();

            if (request('id')) {
                $attribute = Attribute::find($request->input('id'));
                $attribute->fill($input)->save();
                if (count($updateValue)) {
                    foreach ($updateValue['value'] as $k => $val) { //update attribute value
                        AttributeValue::find($updateID[$k])->fill(['name' => $val])->save();
                    }
                }

            } else {
                $attribute = Attribute::create($input);
            }

            if ($attribute AND is_array($selectValue) AND count($selectValue) > 0) {
                $name = $this->getAttrValue($selectValue);
                $attribute->values()->createMany($name);
            }

            DB::commit();

            return $this->ajaxJson(true, ['id' => $attribute->id], 0, '');

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception->getMessage() . $exception->getTraceAsString());

            return $this->ajaxJson(false, [], 404, '保存失败');

        }


    }

    protected function getAttrValue($value)
    {
        $name = [];
        foreach ($value as $val) {
            $name[]['name'] = $val;
        }

        return $name;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = Attribute::find($id);

        return LaravelAdmin::content(function (Content $content) use ($attribute) {

            $content->header('编辑商品参数');

            $content->breadcrumb(
                ['text' => '参数管理', 'url' => 'store/attribute', 'no-pjax' => 1],
                ['text' => '编辑商品参数', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '参数管理']

            );

            $content->body(view('store-backend::attribute.edit', compact('attribute')));
        });
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = DB::table(config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation')->where('spec_id', $id)->get();
        if (count($result)) {
            return $this->ajaxJson(false);
        } else {
            $spec = Spec::find($id);
            if ($spec) {
                $spec->specValue()->delete();
                Spec::destroy($id);

                $models = Models::all();
                foreach ($models as $val) {
                    $specArr = $val->spec_ids;
                    if (in_array($id, $specArr)) {
                        $key = array_search($id, $specArr);
                        array_splice($specArr, $key, 1);
                        $val->spec_ids = $specArr;
                        $val->save();
                    }
                }
            }

            return $this->ajaxJson();
        }

    }

    /**
     * 规格值管理
     * @param $id
     */
    public function specValue($id)
    {
        $spec = Spec::find($id);
        $color = config('ibrand.store.color');
        return view('store-backend::specs.value.edit', compact('spec', 'color'));
    }

    public function specValueStore(Request $request)
    {
        $input = $request->except('_token');


        if (isset($input['value'])) {
            $updateData = $input['value'];
            foreach ($updateData as $item) {
                SpecsValue::find($item['id'])->update($item);
            }
        }

        if (isset($input['delete_id'])) {
            $deleteData = $input['delete_id'];
            foreach ($deleteData as $item) {
                SpecsValue::find($item)->update(['status' => 0]);
            }
        }

        if (isset($input['add_value'])) {
            $createData = $input['add_value'];
            $spec = Spec::find($input['spec_id']);
            foreach ($createData as $item) {

                if (count(SpecsValue::judge($item['name'], $input['spec_id']))) {
                    return $this->ajaxJson(false);
                }
            }
            $spec->specValue()->createMany($createData);
        }

        return $this->ajaxJson();

    }

    /**
     * 删除规格值
     * @param $id
     */
    public function delSpecValue($id)
    {
        $result = DB::table(config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation')->where('spec_value_id', $id)->get();
        if (count($result)) {
            return $this->ajaxJson(false);
        } else {
            SpecsValue::find($id)->delete();
            return $this->ajaxJson(true);
        }
    }
}
