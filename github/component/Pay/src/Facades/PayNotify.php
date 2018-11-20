<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Facades;

use iBrand\Component\Pay\Models\Charge;
use Illuminate\Support\Facades\Facade;

class PayNotify extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'ibrand.pay.notify.default';
    }

    public static function success($name = '', Charge $pay)
    {
        $app = $name ? app('ibrand.pay.notify.'.$name) : app('ibrand.pay.notify.default');

        return $app->success($pay);
    }
}
