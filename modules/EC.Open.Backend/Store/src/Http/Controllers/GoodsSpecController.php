<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\Models;
use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use GuoJiangClub\EC\Open\Backend\Store\Model\SpecsValue;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class GoodsSpecController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specs = Spec::all();

        return LaravelAdmin::content(function (Content $content) use ($specs) {

            $content->header('规格列表');

            $content->breadcrumb(
                ['text' => '规格管理', 'url' => 'store/specs', 'no-pjax' => 1],
                ['text' => '规格列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '规格管理']

            );

            $content->body(view('store-backend::specs.index', compact('specs')));
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

            $content->header('规格列表');

            $content->breadcrumb(
                ['text' => '规格管理', 'url' => 'store/specs', 'no-pjax' => 1],
                ['text' => '新建商品规格', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '规格管理']

            );

            $content->body(view('store-backend::specs.create'));
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
        $input = $request->except('id', '_token');
        if (request('id')) {
            $spec = Spec::find($request->input('id'));
            $spec->fill($input);
            $spec->save();

        } else {
            $spec = Spec::create($input);
        }
        return $this->ajaxJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $spec = Spec::find($id);

        return LaravelAdmin::content(function (Content $content) use ($spec) {

            $content->header('规格列表');

            $content->breadcrumb(
                ['text' => '规格管理', 'url' => 'store/specs', 'no-pjax' => 1],
                ['text' => '编辑商品规格', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '规格管理']

            );

            $content->body(view('store-backend::specs.edit', compact('spec')));
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
        if (count($result) OR $id == 2) {
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

        return LaravelAdmin::content(function (Content $content) use ($spec,$color) {

            $content->header('规格列表');

            $content->breadcrumb(
                ['text' => '规格管理', 'url' => 'store/specs', 'no-pjax' => 1],
                ['text' => '编辑商品规格值', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '规格管理']

            );

            $content->body(view('store-backend::specs.value.edit', compact('spec','color')));
        });
    }

    public function getSpeValueData()
    {
        $data = SpecsValue::where('spec_id', request('spec_id'))->orderBy('id', 'desc')->paginate(20);

        return $this->ajaxJson(true, $data);
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
                if (count(SpecsValue::judge($item['name'], $input['spec_id'])) OR !$item['name']) {
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
    public function delSpecValue()
    {
        $id = request('id');
        $result = DB::table(config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation')->where('spec_value_id', $id)->get();
        if (count($result)) {
            return $this->ajaxJson(false);
        } else {
            SpecsValue::find($id)->delete();
            return $this->ajaxJson(true);
        }
    }

    /**
     * 编辑单个规格值
     * @return mixed
     */
    public function editSpecValue()
    {
        $specValue = SpecsValue::find(request('id'));
        $color = config('ibrand.store.color');
        return view('store-backend::specs.value.edit_value', compact('specValue', 'color'));
    }

    /**
     * 保存单个规格值
     * @param Request $request
     * @return mixed
     */
    public function storeSpecValue(Request $request)
    {
        $input = $request->except('_token');
        $id = request('id');
        $specValue = SpecsValue::find($id);

        $rules = array(
            'name' => 'required|unique:'.config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_value,name,' . $id . ',id,spec_id,' . $specValue->spec_id
        );
        $message = array(
            "required" => ":attribute 不能为空",
            "unique" => ":attribute 与现有的规格值重复"
        );

        $attributes = array(
            "name" => '该规格值',
        );

        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();

            return $this->ajaxJson(false, [], 300, $show_warning);

        }


        $specValue->fill($input);
        $specValue->save();
        return $this->ajaxJson();

    }

    public function addSpecValue($spec_id)
    {
        $color = config('ibrand.store.color');
        return view('store-backend::specs.value.add_value', compact('spec_id', 'color'));
    }
}
