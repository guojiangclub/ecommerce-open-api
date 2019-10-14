<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface AdvertItemRepository extends RepositoryInterface
{
    public function getItemsByCode($code,$associate_with = [],$depth = 0, $status = 1);

    public function create(array $attributes, $parentId = 0);

}
