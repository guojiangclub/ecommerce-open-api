<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Advert\Repository;

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
    public function getByCode($code);
}
