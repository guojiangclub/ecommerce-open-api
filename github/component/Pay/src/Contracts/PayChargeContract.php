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

interface PayChargeContract
{
    public function create(array $data, $type = 'default', $app = 'default');

    public function find($charge_id);
}
