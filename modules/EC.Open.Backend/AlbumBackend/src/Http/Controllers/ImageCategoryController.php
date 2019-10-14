<?php
namespace GuoJiangClub\EC\Open\Backend\Album\Http\Controllers;

use iBrand\Backend\Http\Controllers\Controller;
use GuoJiangClub\EC\Open\Backend\Album\Models\Image;
use GuoJiangClub\EC\Open\Backend\Album\Models\ImageCategory;
use GuoJiangClub\EC\Open\Backend\Album\Repository\ImageCategoryRepository;
use Illuminate\Http\Request;
use DB;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;


class ImageCategoryController extends Controller
{

    protected $imageCategoryRepository;

    public function __construct(ImageCategoryRepository $imageCategoryRepository)
    {
        $this->imageCategoryRepository = $imageCategoryRepository;
    }

    public function index()
    {
        $categories = $this->imageCategoryRepository->getLevelCategory();

        return LaravelAdmin::content(function (Content $content) use ($categories) {

            $content->header('图片分组列表');

            $content->breadcrumb(
                ['text' => '图片分组列表', 'url' => 'store/image/category', 'no-pjax' => 1],
                ['text' => '图片分组列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '图片分类管理']

            );

            $content->body(view('file-manage::category.index', compact('categories')));
        });
    }

    public function edit($id)
    {
        $category = $this->imageCategoryRepository->find($id);
        $categories = $this->imageCategoryRepository->getLevelCategory(0, 0, '&nbsp;&nbsp;');

        return view('file-manage::category.edit', compact('category', 'categories'));
    }

    public function create()
    {
        $category = new ImageCategory();
        $categories = $this->imageCategoryRepository->getLevelCategory(0, 0, '&nbsp;&nbsp;');

        $parent_id = request('parent_id');
        return view('file-manage::category.edit', compact('category', 'categories', 'parent_id'));
    }

    public function store(Request $request)
    {
        $input = $request->except(['_token', 'id']);

        if (request('id')) {
            $this->imageCategoryRepository->update($input, request('id'));
        } else {
            $this->imageCategoryRepository->create($input);
        }

        return $this->ajaxJson(true);
    }

    public function category_sort(Request $request)
    {
        $input = $request->except('_token');
        $id = $request->input('id');
        $this->imageCategoryRepository->update($input, $id);
        return $this->ajaxJson(true);
    }

    public function delete()
    {
        $id = request('id');

        if ($id == 1) {
            return $this->ajaxJson(false, [], 404, '删除失败');
        }

        $ids = $this->imageCategoryRepository->getChildRecursion($id);
        array_push($ids, $id);
        $category = $this->imageCategoryRepository->find($id);

        try {
            DB::beginTransaction();
            if ($category->parent_id == 0) {  //如果是顶级分类，移动到默认分类组下面去
                Image::whereIn('category_id', $ids)->update(['category_id' => 1]);
            } else {  //否则移动到父级目录
                Image::whereIn('category_id', $ids)->update(['category_id' => $category->parent_id]);
            }
            /* $category->delete();*/
            ImageCategory::destroy($ids);

            DB::commit();
            return $this->ajaxJson(true);
        } catch (\Exception $exception) {
            \Log::info($exception);
            DB::rollBack();
            return $this->ajaxJson(false, [], 404, '删除失败');
        }

    }
}