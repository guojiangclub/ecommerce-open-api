<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\EC\Open\Backend\Store\Model\CategoryGroup;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class CategoryGroupController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = CategoryGroup::all();
        return LaravelAdmin::content(function (Content $content) use ($group) {

            $content->header('分类组');

            $content->breadcrumb(
                ['text' => '分类管理', 'url' => 'store/category_group', 'no-pjax' => 1],
                ['text' => '分类组', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']

            );

            $content->body(view('store-backend::category_group.index', compact('group')));
        });

//        return view('store-backend::category_group.index', compact('group'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('新建分类组');

            $content->breadcrumb(
                ['text' => '分类管理', 'url' => 'store/category_group', 'no-pjax' => 1],
                ['text' => '新建分类组', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']

            );

            $content->body(view('store-backend::category_group.create'));
        });

//        return view('store-backend::category_group.create');
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

        if ($groupID = request('id')) {
            $group = CategoryGroup::find($groupID);
            $group->fill($input);
            $group->save();

        } else {
            $group = CategoryGroup::create($input);
        }

        return response()->json(['status' => true
            , 'error_code' => 0
            , 'error' => ''
            , 'data' => $group]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = CategoryGroup::find($id);
        return LaravelAdmin::content(function (Content $content) use ($group) {

            $content->header('修改分类组');

            $content->breadcrumb(
                ['text' => '分类管理', 'url' => 'store/category_group', 'no-pjax' => 1],
                ['text' => '修改分类组', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']

            );

            $content->body(view('store-backend::category_group.edit', compact('group')));
        });
//        return view('store-backend::category_group.edit', compact('group'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = CategoryGroup::find($id)->category()->get();

        if (count($result)) {
            return response()->json(['status' => false
                , 'error_code' => 0
                , 'error' => '该分组下存在分类'
                , 'data' => $result]);
        } else {

            CategoryGroup::destroy($id);
            return response()->json(['status' => true
                , 'error_code' => 0
                , 'error' => ''
                , 'data' => '']);
        }


    }
}
