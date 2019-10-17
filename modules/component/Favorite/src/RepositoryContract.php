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

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RepositoryContract.
 */
interface RepositoryContract extends RepositoryInterface
{
    /**
     * @param      $userId
     * @param      $type
     * @param null $limit
     *
     * @return mixed
     */
    public function getByUserAndType($userId, $type, $limit = null);

    /**
     * @param $userId
     * @param $favoriteableId
     * @param $favoriteableType
     *
     * @return mixed
     */
    public function isFavorite($userId, $favoriteableId, $favoriteableType);

    /**
     * @param $userId
     * @param $favoriteableId
     * @param $favoriteableType
     *
     * @return mixed
     */
    public function add($userId, $favoriteableId, $favoriteableType);

    /**
     * @param       $userId
     * @param array $ids
     *
     * @return mixed
     */
    public function delFavorites($userId, array $ids);
}
