<?php

/*
 * This file is part of ibrand/core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'database' => [
        'prefix' => 'ibrand_'
    ],

    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    |If your page is going to be accessed via https, set it to `true`.
    |
    */
    'secure' => env('SECURE', false),

    'pay_debug' => env('PAY_DEBUG', false),

    'point' => [
        'enable' => true
    ],
];
