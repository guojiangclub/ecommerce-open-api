<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Contracts;

use iBrand\Component\Pay\Models\Charge;

interface PayNotifyContract
{
    public function success(Charge $charge);
}
