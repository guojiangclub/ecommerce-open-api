<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Transformers;

class CouponTransformer extends BaseTransformer
{
    public function transformData($model)
    {
        $coupons = $model->toArray();

        return $coupons;
    }
}
