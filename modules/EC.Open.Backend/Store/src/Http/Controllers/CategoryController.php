<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\Category;
use GuoJiangClub\EC\Open\Backend\Store\Model\GoodsCategory;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CategoryRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {

        $categories = $this->categoryRepository->getLevelCategory();

        return LaravelAdmin::content(function (Content $content) use ($categories) {

            $content->header('分类列表');

            $content->breadcrumb(
                ['text' => '分类列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']
            );

            $content->body(view('store-backend::category.index', compact('categories')));
        });
    }

    public function create()
    {
        $categories = $this->categoryRepository->getLevelCategory(0, '&nbsp;&nbsp;');
        foreach ($categories as $k => $c) {
            if ($c->level > 1) {
                unset($categories[$k]);
            }
        }
        $category = new Category();

        return LaravelAdmin::content(function (Content $content) use ($categories, $category) {

            $content->header('添加分类');

            $content->breadcrumb(
                ['text' => '添加分类', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']
            );

            $content->body(view('store-backend::category.create', compact('categories', 'category')));
        });
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        if (!$input['name']) {
            return $this->ajaxJson(false, [], 500, '请填写分类名称');
        }

        $category = $this->categoryRepository->create($input);

        $this->categoryRepository->setCategoryLevel($category->id, $input['parent_id']);

        return $this->ajaxJson();
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        $categories = $this->categoryRepository->getLevelCategory(0, '&nbsp;&nbsp;');
        foreach ($categories as $k => $c) {
            if ($c->level > 1) {
                unset($categories[$k]);
            }
        }

        return LaravelAdmin::content(function (Content $content) use ($categories, $category) {

            $content->header('修改分类');

            $content->breadcrumb(
                ['text' => '修改分类', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '分类管理']
            );

            $content->body(view('store-backend::category.edit', compact('categories', 'category')));
        });
    }

    public function update(Request $request, $id)
    {
        $input = $request->except('_token');
        if (!$input['name']) {
            return $this->ajaxJson(false, [], 500, '请填写分类名称');
        }
        $category = $this->categoryRepository->update($input, $id);

        $this->categoryRepository->setCategoryLevel($category->id, $input['parent_id']);
        $this->categoryRepository->setSonCategoryLevel($category->id);

        return $this->ajaxJson();
    }

    public function check()
    {
        $status = true;
        $id = request('id');
        $ids = Category::where('parent_id', $id)->pluck('id')->toArray();
        array_push($ids, $id);
        $goods = GoodsCategory::whereIn('category_id', $ids);
        if ($goods->first()) {
            $status = false;
        }
        return $this->ajaxJson($status);
    }

    public function destroy()
    {
        $status = false;
        $id = request('id');
        if ($this->categoryRepository->delCategory($id)) {
            $status = true;
        }
        return $this->ajaxJson($status);
    }

    public function category_sort(Request $request)
    {
        $input = $request->except('_token');
        $id = $request->input('id');
        $this->categoryRepository->update($input, $id);
        return $this->ajaxJson();
    }

}
