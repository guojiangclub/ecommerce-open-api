<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">支付宝支付APPID</label>
        <span class="help-block m-b-none text-gray">* 支付宝开放平台 - PID 和公钥管理 - 开放平台密钥 - APPID</span>
        <span class="help-block m-b-none text-gray">* 签约并添加 APP 支付功能的应用 ID</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_alipay_app_id"
                                  value="{{settings('ibrand_alipay_app_id')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">支付宝异步通知url</label>
        <span class="help-block m-b-none text-gray">* 请勿修改此项设置</span>
    </div>

    <div class="col-sm-10"><input type="text"
                                  value="{{url('api/ali_notify')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">支付宝同步通知url</label>
        <span class="help-block m-b-none text-gray">* 请勿修改此项设置</span>
    </div>

    <div class="col-sm-10"><input type="text"
                                  value="{{url('api/ali_return')}}"
                                  class="form-control"></div>
</div>


<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">支付宝公共密钥</label>
        <span class="help-block m-b-none text-gray">* mapi：支付宝开放平台 - 密钥管理 - mapi网关产品密钥 - RSA(SHA1)密钥 - 查看支付宝公钥</span>
<span class="help-block m-b-none text-gray">* openapi：① RSA：支付宝开放平台 - 密钥管理 - 开放平台密钥 - RSA(SHA1)密钥 - 查看支付宝公钥
② RSA2：支付宝开放平台 - 密钥管理 - 开放平台密钥 - RSA2(SHA256)密钥 - 查看支付宝公钥</span>
    </div>

    <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="ibrand_alipay_ali_public_key">{{settings('ibrand_alipay_ali_public_key')}}</textarea>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">支付宝商户私钥</label>
        <span class="help-block m-b-none text-gray">* 非 PKCS8 编码</span>
<span class="help-block m-b-none text-gray">* mapi：与「支付宝开放平台 - 密钥管理 - mapi网关产品密钥 - RSA(SHA1)密钥」中设置的应用公钥对应的应用私钥</span>
<span class="help-block m-b-none text-gray">* openapi：① RSA：与「支付宝开放平台 - 密钥管理 - 开放平台密钥 - RSA(SHA1)密钥」中设置的应用公钥对应的应用私钥
② RSA2：与「支付宝开放平台 - 密钥管理 - 开放平台密钥 - RSA2(SHA256)密钥」中设置的应用公钥对应的应用私钥</span>
    </div>

    <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="ibrand_alipay_private_key"></textarea>
    </div>
</div>