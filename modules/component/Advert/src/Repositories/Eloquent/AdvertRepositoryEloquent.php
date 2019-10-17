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

use GuoJiangClub\Component\Advert\Models\Advert;
use GuoJiangClub\Component\Advert\Repositories\AdvertRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

class AdvertRepositoryEloquent extends BaseRepository implements AdvertRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Advert::class;
    }

    /**
     * get advert by code.
     *
     * @param $code
     *
     * @return mixed
     */
    public function getByCode($code,$status=1)
    {
        return $this->findByField('code', $code)->where('status',$status)->first();
    }
}
