<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Category;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use DB;


/**
 * Class Repository.
 */
class Repository extends BaseRepository implements RepositoryContract
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
     * @param array $attributes
     * @param int $parentId
     * @return mixed
     */
    public function create(array $attributes, $parentId = 0)
    {
        if ($parentId) {
            $attributes['parent_id'] = $parentId;
        }
        return parent::create($attributes);
    }


    /**
     * @param $categoryId
     * @param bool $excludeSelf
     * @return array|mixed
     */
    public function getSubIdsById($categoryId, $excludeSelf = false)
    {
        if (!$this->allowedCache('getSubIdsById') || $this->isSkippedCache()) {
            return $this->getSubIds($categoryId, $excludeSelf);
        }

        $key = $this->getCacheKey('getSubIdsById', func_get_args());
        $minutes = $this->getCacheMinutes();

        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($categoryId, $excludeSelf) {
            return $this->getSubIds($categoryId, $excludeSelf);
        });

        return $value;
    }

    /**
     * @param $categoryId
     * @param bool $excludeSelf
     * @return array
     */
    private function getSubIds($categoryId, $excludeSelf = false)
    {
        $subIds = $this->model->descendantsOf($categoryId)->pluck('id')->toArray();

        return $excludeSelf ? $subIds : array_merge([$categoryId], $subIds);
    }


    /**
     * @param int $depth
     * @return mixed
     */
    public function getCategories($depth = 0)
    {

        if (!$this->allowedCache('getCategories') || $this->isSkippedCache()) {
            return $this->getCategoriesInfo($depth);
        }

        $key = $this->getCacheKey('getCategories', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($depth) {
            return $this->getCategoriesInfo($depth);
        });
        return $value;
    }

    /**
     * @return mixed
     */
    private function getCategoriesInfo($depth = 0)
    {
        $query = $this->model->orderBy('sort', 'Asc');
        if (!$depth) {
            $query = $query->get();
        } else {
            $sub = $this->model->withDepth();
            $query = $this->model->from(DB::raw("({$sub->toSql()}) as sub"))
                ->where('depth', '<', $depth)->orderBy('sort', 'Asc')->get();
        }
        /*$sub = $this->model->withDepth();

        $query = $this->model->from(DB::raw("({$sub->toSql()}) as sub"))
            ->orderBy('sort', 'Asc');*/

        return $query->toTree();
    }


    /**
     * @param $catKeyword
     * @param int $depth
     * @return mixed
     */
    public function getSubCategoriesByNameOrId($catKeyword, $depth = 0)
    {

        if (!$this->allowedCache('getSubCategoriesByNameOrId') || $this->isSkippedCache()) {
            return $this->getSubCategories($catKeyword, $depth);
        }

        $key = $this->getCacheKey('getSubCategoriesByNameOrId', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($catKeyword, $depth) {
            return $this->getSubCategories($catKeyword, $depth);
        });

        return $value;

    }


    private function getSubCategories($catKeyword, $depth = 0)
    {

        $rootId = $this->model->Where('name', '=', $catKeyword)->orWhere('id', $catKeyword)->value('id');

        if (!empty($rootId)) {

            if (empty($depth)) {

                $res = $this->model->descendantsOf($rootId)->toTree($rootId);

            } else {
                $res = $this->model->withDepth()->where('depth', '<=', $depth)->orderBy('sort', 'Asc')->get()->toTree($rootId);

                //$sub = $this->model->withDepth();

                //$depthRoot = $this->model->withDepth()->find($rootId)->depth;

                //$res = Category::from(DB::raw("({$sub->toSql()}) as sub"))->mergeBindings($sub)
                //->where('depth', '<', $depth + $depthRoot)->orderBy('sort', 'Asc')->get()->toTree($rootId);
            }

        } else {
            /**
             * if not found the parent , it will return the whole tree
             */
            $res = $this->model->orderBy('sort', 'Asc')->get()->toTree();

        }

        return $res;
    }

}
