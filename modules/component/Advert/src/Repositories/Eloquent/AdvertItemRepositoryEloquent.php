<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Advert\Repository\Eloquent;

use iBrand\Component\Advert\Models\AdvertItem;
use iBrand\Component\Advert\Repository\AdvertItemRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

class AdvertItemRepositoryEloquent extends BaseRepository implements AdvertItemRepository
{
    use CacheableRepository;

    const AVAILABLE = 1; // 可用状态

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return AdvertItem::class;
    }

    public function getItemsByCode($code)
    {
        return $this->whereHas('advert', function ($query) use ($code) {
            return $query->where('code', $code);
        })->get();
    }
}
