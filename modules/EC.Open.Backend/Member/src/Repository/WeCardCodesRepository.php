<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Repository;

use iBrand\EC\Open\Backend\Member\Models\WeCardCodes;
use Prettus\Repository\Eloquent\BaseRepository;

class WeCardCodesRepository extends BaseRepository
{
    public function model()
    {
        return WeCardCodes::class;
    }
}
