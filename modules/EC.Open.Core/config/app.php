<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) 果酱社区 <https://guojiang.club>
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

    /*
     |--------------------------------------------------------------------------
     | 积分系统相关设置
     |--------------------------------------------------------------------------
     *
     * enable : 是否启用积分系统
     * order_proportion: 积分兑换金额比例（单位：分）：举例：值为10：表示1个积分值10分钱。因为订单系统所有金额单位都是分
     * order_limit：订单使用积分所占订单金额上线，单位：百分比。值为50：表示最多使用积分抵扣的金额最多占整个订单金额的50%
     */
    'point' => [
        'enable' => true,
        'order_proportion' => 10,
        'order_limit' => 50
    ],

    /*
    |--------------------------------------------------------------------------
    | 订单未付款自动取消时间，单位分钟
    |--------------------------------------------------------------------------
    */
    'order_auto_cancel' => 30
];
