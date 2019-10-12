<?php
namespace GuoJiangClub\EC\Open\Backend\Album\Repository;

use GuoJiangClub\EC\Open\Backend\Album\Models\Image;
use GuoJiangClub\EC\Open\Backend\Album\Models\ImageCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class ImageCategoryRepository extends BaseRepository
{

    public function model()
    {
        return ImageCategory::class;
    }

    /**
     * 获得排序分类
     * @return mixed
     */
    public function getSortCategory()
    {
        $category = $this->scopeQuery(function ($query) {
            return $query->orderBy('sort', 'asc');
        })->all(['id', 'name', 'parent_id', 'sort']);

        foreach ($category as $v) {
            $v['id'] = strval($v['id']);
            $v['parent_id'] = strval($v['parent_id']);
            $v['sort'] = strval($v['sort']);
        }
        return $category;
    }

    /**
     * 无限极分类
     * @param int $pid
     * @param int $level
     * @return array
     */
    public function getLevelCategory($pid = 0, $level = 0, $html = '', $dep = '')
    {
        $categories = $this->getSortCategory();

        return $this->buildCategoryTree($categories, $pid, $level, $html, $dep);
    }

    private function buildCategoryTree($categories, $pid = 0, $level = 0, $html = ' ', $dep = '')
    {
        $result = array();
        foreach ($categories as $v) {
            if ($v['parent_id'] == $pid) {
                $v['level'] = $level + 1;
                $v['html'] = str_repeat($html, $level);
                $v['dep'] = $dep . '_' . $v['id'];
                $result[] = $v;
                $result = array_merge($result, self::buildCategoryTree($categories, $v['id'], $level + 1, $html, $v['dep']));
            }
        }
        return $result;
    }

    public function getChildRecursion($id)
    {
        $category = $this->findWhere(['parent_id' => $id]);
        $result = [];
        if (count($category) > 0) {
            foreach ($category as $item) {
                $result[] = $item->id;
                $result = array_merge($result, self::getChildRecursion($item->id));
            }
        }
        return $result;
    }


    public function getTree($data, $pid)
    {
        $tree = [];
        foreach ($data as $key => $item) {
            if ($item['parent_id'] == $pid) {
                $item['nodes'] = self::getTree($data, $item['id']);
                $tree[] = $item;
            }
        }
        return $tree;
    }


    public function getSortTree($category_id)
    {
        $data = $this->getSortCategory();
        $tree = [];
        foreach ($data as $key => $value) {
            $tree[$key]['id'] = $value['id'];
            $tree[$key]['text'] = $value['name'];
            $tree[$key]['parent_id'] = $value['parent_id'];
            $tree[$key]['tags'] = [Image::where('category_id', $value['id'])->count()];
            $tree[$key]['href'] = route('admin.image.index', ['category_id' => $value['id']]);
            if ($category_id == $value['id']) {
                $tree[$key]['state']['selected'] = true;
            }
        }
        return $tree;
    }


}