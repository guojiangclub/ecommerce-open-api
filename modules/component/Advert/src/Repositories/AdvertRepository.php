<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface AdvertRepository extends RepositoryInterface
{
    /**
     * get advert by code.
     *
     * @param $code
     *
     * @return mixed
     */
    public function getByCode($code,$status=1);
}
