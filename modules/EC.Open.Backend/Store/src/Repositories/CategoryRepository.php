<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\Category;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CategoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * 获得排序分类
     * @return mixed
     */
    public function getSortCategory()
    {
        $category = $this->orderBy('sort', 'asc')->all(['id', 'name', 'parent_id', 'sort', 'level', 'path']);
        return $category;
    }

    /**
     * 无限极分类
     * @param int $pid
     * @param int $level
     * @return array
     */
    public function getLevelCategory($pid = 0, $html = ' ', $dep = '')
    {

        $categories = $this->getSortCategory();

        return $this->buildCategoryTree($categories, $pid, $html, $dep);

    }

    private function buildCategoryTree($categories, $pid = 0, $html = ' ', $dep = '')
    {
        $result = [];
        foreach ($categories as $v) {
            if ($v['level'] > 2) {
                continue;
            }

            if ($v['parent_id'] == $pid) {
                $v['html'] = str_repeat($html, $v['level']);
                $v['dep'] = $v['path'];
                $result[] = $v;
                $result = array_merge($result, self::buildCategoryTree($categories, $v['id'], $html, $v['dep']));
            }
        }

        return $result;
    }

    public function getOneLevelCategory($pid = 0)
    {
        $categories = $this->getSortCategory();
        $result = array();
        foreach ($categories as $v) {
            if ($v['level'] > 2) {
                continue;
            }

            if ($v['parent_id'] == $pid) {
                $result[] = $v;
            }
        }

        return $result;
    }

    /**
     * 根据商品分类的父类ID进行数据归类
     * @return array
     */
    public function getCategoryParent()
    {
        $categoryData = $this->getSortCategory();
        $result = array();
        foreach ($categoryData as $key => $val) {
            if (isset($result[$val['parent_id']]) && is_array($result[$val['parent_id']])) {
                $result[$val['parent_id']][] = $val;
            } else {
                $result[$val['parent_id']] = array($val);
            }
        }
        return $result;
    }

    public function delCategory($id)
    {

        if (count($this->findWhere(['parent_id' => $id])) > 0) return false;

        if ($this->delete($id)) return true;

        return false;
        /*if ($this->delete($id))
            if (Category::where('parent_id', $id)->delete())
                return true;
        return false;*/
    }

    /**
     * 设置分类的depth level
     * @param $category_id
     * @param $parent_id
     */
    public function setCategoryLevel($category_id, $parent_id)
    {
        $category = $this->find($category_id);
        if ($parent_id) {
            $parentCategory = $this->find($parent_id);
            $category->path = $parentCategory->path . $category->id . '/';
            $category->level = $parentCategory->level + 1;
        } else {
            $category->path = '/' . $category->id . '/';
            $category->level = 1;
        }
        $category->save();
    }

    /**
     * 设置子分类的path level
     * @param $category_id
     */
    public function setSonCategoryLevel($category_id)
    {
        $sonCategory = $this->scopeQuery(function ($query) use ($category_id) {
            $query->where('path', 'like', '%/' . $category_id . '/%')
                ->where('id', '<>', $category_id);
            return $query->orderBy('level', 'asc');
        })->all();

        $category = $this->find($category_id);

        if (count($sonCategory) > 0) {
            $this->setSonCategoryLevelTree($sonCategory, $category);
        }
    }

    private function setSonCategoryLevelTree($categories, $parent_category)
    {
        foreach ($categories as $key => $item) {
            if ($item->parent_id == $parent_category->id) {
                $item->path = $parent_category->path . $item->id . '/';
                $item->level = $parent_category->level + 1;
                $item->save();
                self::setSonCategoryLevelTree($categories, $item);
            }
        }
    }

}
