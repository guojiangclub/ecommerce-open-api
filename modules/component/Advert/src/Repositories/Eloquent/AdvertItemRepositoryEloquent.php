<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert\Repositories\Eloquent;

use GuoJiangClub\Component\Advert\Models\AdvertItem;
use GuoJiangClub\Component\Advert\Repositories\AdvertItemRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use DB;

class AdvertItemRepositoryEloquent extends BaseRepository implements AdvertItemRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return AdvertItem::class;
    }

    /**
     * @param array $attributes
     * @param int $parentId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(array $attributes, $parentId = 0)
    {
        if ($parentId) {
            $attributes['parent_id'] = $parentId;
        }

        return parent::create($attributes);
    }


    public function getItemsByCode($code,$associate_with = [],$depth = 0, $status = 1)
    {

        $advert = $this->whereHas('advert', function ($query) use ($code,$status) {
            return $query->where('code', $code)->where('status', $status);
        })->first();

        if (!$advert) {
            return null;
        }

        $query = $this->model->with('associate');

        if (count($associate_with)>0) {

            foreach ($associate_with as $with){

                $query = $query->with('associate.'.$with);
            }

        }

        $query = $query->where('advert_id', $advert->advert_id)
            ->where('status', $status)
            ->orderBy('sort');

        if (!$depth) {
            $query = $query->get();
        } else {
            $sub = $this->model->withDepth();

            $query = $query->from(DB::raw("({$sub->toSql()}) as sub"))
                ->where('depth', '<', $depth)->get();
        }

        return $query->toTree();


    }
}
