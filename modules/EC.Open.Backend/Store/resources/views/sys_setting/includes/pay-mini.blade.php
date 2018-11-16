<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">小程序APPID</label>
        <span class="help-block m-b-none text-gray">* 微信公众平台 | 小程序 - 设置 - 开发设置 - AppID</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_miniapp_id"
                                  value="{{settings('ibrand_miniapp_pay_miniapp_id')}}"
                                  class="form-control"></div>
</div>


<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">小程序商户号MCHID</label>
        <span class="help-block m-b-none text-gray">* 申请小程序支付时，若选择使用已有的公众号支付，请填写公众号支付商户号；
否则，请在新申请的微信支付商户平台的通知邮件中获取商户号，请确保邮件中的小程序 AppID 与本次填写的小程序 AppID 一致</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_mch_id"
                                  value="{{settings('ibrand_miniapp_pay_mch_id')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">小程序支付签名秘钥</label>
        <span class="help-block m-b-none text-gray">* 微信支付商户平台 - 账户中心 - 账户设置 - API 安全 - API 密钥 - 设置密钥</span>
    </div>

    <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_key"
                                  value="{{settings('ibrand_miniapp_pay_key')}}"
                                  class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">小程序支付异步通知url</label>
        <span class="help-block m-b-none text-gray">* 请勿修改此项设置</span>
    </div>

    <div class="col-sm-10"><input type="text"
                                  value="{{url('api/wechat_notify')}}"
                                  class="form-control"></div>
</div>