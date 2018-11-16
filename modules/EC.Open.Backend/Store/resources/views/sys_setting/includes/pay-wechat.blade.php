<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">微信支付APPID</label>
        <span class="help-block m-b-none text-gray">* 微信公众平台 - 开发 - 基本配置 - 开发者 ID - AppID</span>
    </div>

    <div class="col-sm-10">
        <input type="text" name="ibrand_wechat_pay_app_id"
               value="{{settings('ibrand_wechat_pay_app_id')}}"
               class="form-control">
    </div>
</div>


<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">微信商户号MCHID</label>
        <span class="help-block m-b-none text-gray">* 在微信支付申请完成后微信支付商户平台的通知邮件中获取，请确保邮件中的公众号 AppID 与本次填写的公众号 AppID 一致</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_wechat_pay_mch_id"
                                  value="{{settings('ibrand_wechat_pay_mch_id')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">微信支付签名秘钥</label>
        <span class="help-block m-b-none text-gray">* 微信支付商户平台 - 账户中心 - 账户设置 - API 安全 - API 密钥 - 设置密钥</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_wechat_pay_key"
                                  value="{{settings('ibrand_wechat_pay_key')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">微信支付异步通知url</label>
        <span class="help-block m-b-none text-gray">* 请勿修改此项设置</span>
    </div>

    <div class="col-sm-10"><input type="text"
                                  value="{{url('api/wechat_notify')}}"
                                  class="form-control"></div>
</div>


<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">API 证书(apiclient_cert)</label>
        <span class="help-block m-b-none text-gray">* 微信支付商户平台 - 账户中心 - 账户设置 - API 安全 - API 证书 - 下载证书</span>
    </div>

    <div class="col-sm-10">
        <textarea class="form-control" rows="4" name="ibrand_wechat_pay_apiclient_cert"></textarea>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">API 证书密钥(apiclient_key) </label>
        <span class="help-block m-b-none text-gray">* 微信支付商户平台 - 账户中心 - 账户设置 - API 安全 - API 证书 - 下载证书</span>
    </div>
    <div class="col-sm-10">
        <textarea class="form-control" rows="4" name="ibrand_wechat_pay_apiclient_key"></textarea>
    </div>
</div>