<?php

/*
 * This file is part of ibrand/favorite.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Favorite;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

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
        return Favorite::class;
    }

    /**
     * @param      $userId
     * @param      $type
     * @param null $limit
     *
     * @return mixed
     */
    public function getByUserAndType($userId, $type, $limit = null)
    {
        return $this->scopeQuery(function ($query) use ($userId, $type) {
            return $query->where('user_id', $userId)->where('favoriteable_type', $type);
        })->with('favoriteable')->paginate($limit);
    }

    /**
     * @param $userId
     * @param $favoriteableId
     * @param $favoriteableType
     *
     * @return mixed
     */
    public function isFavorite($userId, $favoriteableId, $favoriteableType)
    {
        return $this->model->where(['user_id' => $userId, 'favoriteable_id' => $favoriteableId, 'favoriteable_type' => $favoriteableType])->first();
    }

    /**
     * @param $userId
     * @param $favoriteableId
     * @param $favoriteableType
     *
     * @return mixed
     */
    public function add($userId, $favoriteableId, $favoriteableType)
    {
        return $this->firstOrCreate(['user_id' => $userId, 'favoriteable_id' => $favoriteableId, 'favoriteable_type' => $favoriteableType]);
    }

    /**
     * @param       $userId
     * @param array $ids
     *
     * @return mixed
     */
    public function delFavorites($userId, array $ids)
    {
        return $this->scopeQuery(function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        })->deleteWhere(['user_id' => $userId]);
    }
}
