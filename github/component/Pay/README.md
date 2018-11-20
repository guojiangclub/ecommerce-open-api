# Laravel 多应用多配置支付包

## Feature

1. 实现多渠道支付方式，比如：原生支付，pingxx支付，银商支付等。
2. 实现多业务统一支付。

## TODO:
1. 实现退款

## 安装

```
$ composer require ibrand/pay
$ php artisan vendor:publish
$ php artisan migrate
```
## 使用

### 创建支付请求

发起一次支付请求时需要创建一个新的 charge 对象，获取一个可用的支付凭据用于客户端向第三方渠道发起支付请求。

```php
        use iBrand\Component\Pay\Facades\Charge;

        $charge = Charge::create(['channel' => 'wx_lite'
            , 'order_no' => $order_no
            , 'amount' => $order->getNeedPayAmount()
            , 'client_ip' => \request()->getClientIp()
            , 'subject' => $order->getSubject()
            , 'body' => $order->getSubject()
            , 'extra' => ['openid' => \request('openid')]
        ]);

        return $this->success(compact('charge'));
```
创建 charge 方法定义如下：
```
public function create(array $data, $type = 'default', $app = 'default');
```
-  $data 参数为支付参数，请见 支付参数一节。
-  $type 为业务类型，如果只有一种支付业务，不传即可。如果存在多种业务支付类型，比如商城中存在支付订单，余额充值订单。
-  $app 具体的应用名称，比如商城APP和活动APP，可能使用不同的支付配置


#### 支付参数

|请求参数      |类型        |必填    |描述|
|:----    |:-------    |:--- |------      |
|order_no	      |string     |是	| 商户订单号，适配每个渠道对此参数的要求，必须在商户的系统内唯一。(支付宝全渠道: 1-64 位的数字和字母组合；`wx`: 2-32 位；`wx_pub_scan、cb_wx、cb_wx_pub、cb_wx_pub_qr、cb_wx_pub_scan`:1-32 位的数字和字母组合；`bfb`: 1-20 位；银联全渠道: 8-40 位的数字和字母组合； `yeepay_wap`: 1-50 位；`jdpay_wap`: 1-30 位；`qpay`:1-30 位；`isv_qr、isv_scan、isv_wap`: 8-32 位，不重复，建议时间戳+随机数（或交易顺序号）；`paypal`:1-32 位的数字和字母组合；`ccb_pay、ccb_qr`：1-30 位数字和字母组合；`cmb_wallet`: 6-32 位的数字和字母组合，一天内不能重复，订单日期+订单号唯一定位一笔订单，示例: 20170808test01)。**注：**推荐使用 8-20 位的数字和字母组合，不允许特殊字符。 |
|channel	|string |是	| 已经支持值： |
|amount	|int |是	| 订单总金额，必须大于0，单位为分 |
|client_ip	|string |是	| 发起支付的客户端IP地址，Laravel 中 `request()->getClientIp()` |
|subject	|string |是	| 商品标题，该参数最长为 32 个 Unicode 字符。银联全渠道限制在 32 个字节；支付宝部分渠道不支持特殊字符。 |
|body	|string |是	| 商品描述信息，该参数最长为 128 个 Unicode 字符。`yeepay_wap` 对于该参数长度限制为 100 个 Unicode 字符；支付宝部分渠道不支持特殊字符。 |
|extra	|array |是	| 在微信公众号支付，小程序支付，需要传递openid。如：`'extra' => ['openid' => \request('openid')]` |
|time_expire	|timestamp |否	| 订单失效时间。时间范围在订单创建后的 1 分钟到 15 天，默认为 1 天，创建时间以 Ping++ 服务器时间为准。微信对该参数的时间范围在订单创建后的 1 分钟到 7 天，默认为 2 小时；`upacp、upacp_pc、upacp_wap、cp_b2b、applepay_upacp` 渠道对该参数的有效值限制为 1 小时内；`upacp_b2b` 对该参数的有效值限制为 1 天内；`upacp_qr` 渠道对该参数的有效期默认为 1 天，最大为 30 天。 |
|metadata	|array |否	| 附属参数，会传递给微信和支付宝 |
|description	|string |否	| 订单附件说明 |

#### 返回值

发起支付请求后，微信通道会返回 charge 值，如下：

```
{
            "app": "default",  // 应用名称
            "type": "default", // 业务类型，
            "channel": "wx_lite",
            "order_no": "O2018111919576278725",
            "amount": 4900,
            "client_ip": "222.247.67.172",
            "subject": "【秋款】2018年儿童秋款T 儿童打底衫秋款 竹节棉阔形儿童T恤 等1件商品",
            "body": "【秋款】2018年儿童秋款T 儿童打底衫秋款 竹节棉阔形儿童T恤 等1件商品",
            "extra": {
                "openid": "ozJEr5NrFkYrNGC5rftcOAO4c3OE"
            },
            "charge_id": "ch_QAjx7VNJxp2mstAb8kPc0oZR",
            "updated_at": "2018-11-20 09:36:45",
            "created_at": "2018-11-20 09:36:44",
            "id": 4,
            "credential": {  //客户端支付凭证
                "wechat": {
                    "appId": "wx0ae854a59b88cd63",
                    "timeStamp": "1542706604",
                    "nonceStr": "2nbKpiNEY0qHbMaM",
                    "package": "prepay_id=wx201736451424458ba271c9481469876463",
                    "signType": "MD5",
                    "paySign": "8677CEAEDE07E1FCB2E18A460805D98C"
                }
            },
            "out_trade_no": "wx_w5FZYpe0sEyxuCuXF0p3NP_b"
}
```

### 异步通知

本包会统一处理支付异步通知，可以通过配置文件来修改异步通知的 url。本报收到异步通知后，会执行  `PayNotify::success($charge->type, $charge);` 来通知业务方已经完成支付。

业务方在自己的代码中只需要做以下事情即可：
- 实现 `iBrand\Component\Pay\Contracts\PayNotifyContract` 接口，在 `success(Charge $charge)` 方法中完成业务订单的状态变更。
- 在 ServiceProvider 中绑定 `PayNotifyContract` 实现。 `$this->app->bind('ibrand.pay.notify.default',StorePayNotify::class);` 其中 `default` 为业务类型值，需要保持和创建 charge 对象时传递的 `$type` 值一致。 

### 同步查询

通常的业务流程中支付完成后，会跳转到支付成功页面，此时需要去主动同步查询订单状态，防止异步通信异常导致订单状态未正确变更的问题。

```
$charge = Charge::find(request('charge_id'));

$order = PayNotify::success($charge->type, $charge);
```

## 配置项

执行 `php artisan vendor:publish` 后会发布配置文件 `config/ibrand/pay.php`

```php
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
```

## Resources

[yansongda/pay](https://github.com/yansongda/pay)
