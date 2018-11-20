<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Charges;

use Hidehalo\Nanoid\Client;
use iBrand\Component\Pay\Contracts\PayChargeContract;

abstract class BaseCharge implements PayChargeContract
{
    protected function getWxPayCode($order_sn, $channel)
    {
        switch ($channel) {
            case 'wx_pub':
            case 'wx_pub_qr':
            case 'wx_lite':
                $client = new Client();

                return 'wx_'.$client->formatedId('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz-', 24);
            default:
                return $order_sn;
        }
    }

    protected function validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = 'You must pass an array as the first argument to pay API '
                .'method calls.';
            throw new \InvalidArgumentException($message);
        }
    }
}
