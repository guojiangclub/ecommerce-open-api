<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
     * 异步通知路由参数
     */
    'route' => [
        'prefix' => 'notify',
        'middleware' => ['api'],
    ],

    /*
     * 默认的支付渠道类型，default 默认支付驱动类型基于 yansongda/pay 支付宝实现
     */
    'driver' => 'default',

    'default' => [
        'alipay' => [

            /*
             * APP_NAME，不同的应用会使用不同的支付参数，举例：
             * 在 iBrand 有商城订单支付，有活动报名支付，两个小程序是不同的 appid 甚至是不同的支付主体，所以需要配置不同的支付参数
             *
             */
            'default' => [
                // 支付宝分配的 APPID
                'app_id' => env('ALI_PAYMENT_APP_ID', ''),
                // 支付宝异步通知地址
                'notify_url' => '/notify/alipay',
                // 支付成功后同步通知地址
                'return_url' => '',
                // 阿里公共密钥，验证签名时使用
                'ali_public_key' => env('ALI_PAYMENT_PUBLIC_KEY', ''),
                // 自己的私钥，签名时使用
                'private_key' => env('ALI_PAYMENT_PRIVATE_KEY', ''),
                // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
                'log' => [
                    'file' => storage_path('logs/alipay.log'),
                    //  'level' => 'debug'
                    'type' => 'single', // optional, 可选 daily.
                    'max_file' => 30,
                ],
                // optional，设置此参数，将进入沙箱模式
                // 'mode' => 'dev',
            ],
        ],

        'wechat' => [

            'default' => [
                // 公众号 APPID
                'app_id' => env('WECHAT_PAYMENT_APP_ID', ''),
                // 小程序 APPID
                'miniapp_id' => env('WECHAT_PAYMENT_MINIAPP_ID', ''),
                // APP 引用的 appid
                'appid' => env('WECHAT_PAYMENT_APPID', ''),
                // 微信支付分配的微信商户号
                'mch_id' => env('WECHAT_PAYMENT_MCH_ID', ''),
                // 微信支付异步通知地址
                'notify_url' => '/notify/wechat',
                // 微信支付签名秘钥
                'key' => env('WECHAT_PAYMENT_KEY', ''),
                // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
                'cert_client' => '',
                // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
                'cert_key' => '',
                // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
                'log' => [
                    'file' => storage_path('logs/wechat.log'),
                    //  'level' => 'debug'
                    'type' => 'single', // optional, 可选 daily.
                    'max_file' => 30,
                ],
                // optional
                // 'dev' 时为沙箱模式
                // 'hk' 时为东南亚节点
                // 'mode' => 'dev',
            ],
            // ...
        ],
    ],
];
