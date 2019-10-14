<?php

namespace GuoJiangClub\EC\Open\Backend\Album\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Album\Models\Image;
use GuoJiangClub\EC\Open\Backend\Album\Models\ImageCategory;
use GuoJiangClub\EC\Open\Backend\Album\Repository\ImageRepository;
use GuoJiangClub\EC\Open\Backend\Album\Repository\ImageCategoryRepository;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class ImagesController extends Controller
{

    protected $imageRepository;
    protected $imageCategoryRepository;

    public function __construct(ImageRepository $imageRepository,
                                ImageCategoryRepository $imageCategoryRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->imageCategoryRepository = $imageCategoryRepository;
    }

    public function index()
    {
        $id = request('category_id');
        $data = $this->imageCategoryRepository->getSortTree($id);
        $categories = $this->imageCategoryRepository->getTree($data, 0);

        $imgList = Image::where('category_id', $id)->orderBy('id', 'desc')->paginate(15);

        $category = ImageCategory::find($id);
        $categorySub = ImageCategory::where('parent_id', $id)->get();

        return LaravelAdmin::content(function (Content $content) use ($imgList, $categories, $category, $categorySub) {

            $content->header('图片管理');

            $content->breadcrumb(
                ['text' => '图片管理', 'url' => 'store/image/file?category_id=1', 'no-pjax' => 1],
                ['text' => '图片列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '图片列表']

            );

            $content->body(view('file-manage::image.index', compact('imgList', 'categories', 'category', 'categorySub')));
        });

    }

    /**
     * 删除图片
     * @return mixed
     */
    public function delete()
    {
        if (request('type') == 'single') {
            $id = request('id');
        } else {
            $id = explode(',', request('id'));
        }

        if (Image::destroy($id)) {
            return $this->ajaxJson(true);
        }
        return $this->ajaxJson(false);
    }

    /**
     * 修改名称
     * @param $id
     * @return mixed
     */
    public function editName($id)
    {
        $image = Image::find($id);

        return view('file-manage::image.includes.edit_name', compact('image'));
    }

    /**
     * 修改单个分组
     * @param $id
     * @return mixed
     */
    public function editImageCategory($id)
    {
        $image = Image::find($id);

        $categories = $this->imageCategoryRepository->getLevelCategory(0, 0, '&nbsp;&nbsp;');

        return view('file-manage::image.includes.edit_image_category', compact('image', 'categories'));
    }

    /**
     * 保存单个修改
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', 'id']);
        $image = $this->imageRepository->update($input, request('id'));
        if ($image) {
            return $this->ajaxJson(true);
        }

        return $this->ajaxJson(false);
    }

    /**
     * 批量修改图片
     */
    public function editImageCategoryBatch()
    {
        $cid = request('cid');
        $ids = request('ids');
        $categories = $this->imageCategoryRepository->getLevelCategory(0, 0, '&nbsp;&nbsp;');

        return view('file-manage::image.includes.edit_image_category_batch', compact('cid', 'categories', 'ids'));
    }

    /**
     * 批量修改
     * @return mixed
     */
    public function saveBatch()
    {
        $ids = explode(',', request('ids'));

        foreach ($images = $this->imageRepository->findWhereIn('id', $ids) as $item) {
            $item->category_id = request('category_id');
            $item->save();
        }
        return $this->ajaxJson(true);
    }

    public function upload()
    {
        $category_id = request('category_id');
        return view('file-manage::image.includes.upload', compact('category_id'));
    }


    /*弹窗接口  图片数据*/
    public function getImageDataModal()
    {
        $id = request('category_id') ? request('category_id') : 1;

        $imgList = Image::where('category_id', $id)->orderBy('id', 'desc')->paginate(15)->toArray();
        $categorySub = ImageCategory::where('parent_id', $id)->get()->toArray();

        if (request('page') == 1) {
            $imgList['sub'] = $categorySub;
        }

        return $this->ajaxJson(true, $imgList);
    }

    /*弹窗接口  分类数据*/
    public function getImageCategoryModal()
    {
        $id = request('category_id') ? request('category_id') : 1;
        $data = $this->imageCategoryRepository->getSortTree($id);
        $categories = $this->imageCategoryRepository->getTree($data, 0);

        return $this->ajaxJson(true, $categories);
    }
}