<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <form method="post" action="{{route('admin.setting.pingxx.pay')}}" class="form-horizontal"
              id="setting_site_form">
            {{csrf_field()}}

            <div class="form-group"><label class="col-sm-2 control-label">启用pingxx支付
                    {{--<i class="fa fa-question-circle"></i>--}}</label>
                <div class="col-sm-10">
                    <input type="radio" value="1" name="enabled_pingxx_pay"
                            {{settings('enabled_pingxx_pay') ? 'checked':''}}> 是 &nbsp;&nbsp;
                    <input type="radio" value="0" name="enabled_pingxx_pay"
                            {{!settings('enabled_pingxx_pay')? 'checked':''}}> 否
                </div>
            </div>


            <div class="form-group"><label class="col-sm-2 control-label">应用ID</label>

                <div class="col-sm-10"><input type="text" name="pingxx_app_id" value="{{settings('pingxx_app_id')}}"
                                              class="form-control"></div>
            </div>

            <div class="form-group"><label class="col-sm-2 control-label">Test Secret Key <i
                            class="fa fa-question-circle"></i></label>

                <div class="col-sm-10"><input type="text" name="pingxx_test_secret_key"
                                              value="{{settings('pingxx_test_secret_key')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Live Secret Key <i
                            class="fa fa-question-circle"></i></label>

                <div class="col-sm-10"><input type="text" name="pingxx_live_secret_key"
                                              value="{{settings('pingxx_live_secret_key')}}"
                                              class="form-control"></div>
            </div>

            <div class="form-group"><label class="col-sm-2 control-label">Ping++公钥 <i
                            class="fa fa-question-circle"></i></label>
                <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="pingxx_rsa_public_key">{{settings('pingxx_rsa_public_key')}}</textarea>
                </div>
            </div>

            <div class="form-group"><label class="col-sm-2 control-label">商户私钥 <i
                            class="fa fa-question-circle"></i></label>
                <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="pingxx_rsa_private_key"></textarea>
                </div>
            </div>

            <div class="form-group"><label class="col-sm-2 control-label">webhooks url <i
                            class="fa fa-question-circle"></i></label>
                <div class="col-sm-10">
                    <p class="form-control">{{url('api/webhooks')}}</p>
                </div>
            </div>

            <div class="form-group"><label class="col-sm-2 control-label">支付场景 <i
                            class="fa fa-question-circle"></i></label>
                <div class="col-sm-10">
                    <input type="radio" value="live" name="pingxx_pay_scene"
                            {{settings('pingxx_pay_scene') == 'live'? 'checked':''}}> live &nbsp;&nbsp;
                    <input type="radio" value="test" name="pingxx_pay_scene"
                            {{settings('pingxx_pay_scene') == 'test'? 'checked':''}}> test
                </div>
            </div>


            <div class="form-group"><label class="col-sm-2 control-label">微信支付APPID</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_app_id"
                                              value="{{settings('wechat_pay_app_id')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">微信支付APP_SECRET</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_app_secret"
                                              value="{{settings('wechat_pay_app_secret')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">微信支付商户号</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_merchant_id"
                                              value="{{settings('wechat_pay_merchant_id')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">微信支付商API密钥</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_merchant_api_key"
                                              value="{{settings('wechat_pay_merchant_api_key')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">支付成功跳转URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_success_url"
                                              value="{{settings('wechat_pay_success_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">支付失败URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_fail_url"
                                              value="{{settings('wechat_pay_fail_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">支付失败返回URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_pay_fail_ucenter"
                                              value="{{settings('wechat_pay_fail_ucenter')}}"
                                              class="form-control"></div>
            </div>


            <div class="form-group"><label class="col-sm-2 control-label">活动支付成功跳转URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_activity_pay_success_url"
                                              value="{{settings('wechat_activity_pay_success_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">活动支付失败URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_activity_pay_fail_url"
                                              value="{{settings('wechat_activity_pay_fail_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">活动支付失败返回URL</label>

                <div class="col-sm-10"><input type="text" name="wechat_activity_pay_fail_ucenter"
                                              value="{{settings('wechat_activity_pay_fail_ucenter')}}"
                                              class="form-control"></div>
            </div>


            <div class="form-group"><label class="col-sm-2 control-label">充值支付成功跳转URL</label>

                <div class="col-sm-10"><input type="text" name="recharge_wechat_pay_success_url"
                                              value="{{settings('recharge_wechat_pay_success_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">充值支付失败URL</label>

                <div class="col-sm-10"><input type="text" name="recharge_wechat_pay_fail_url"
                                              value="{{settings('recharge_wechat_pay_fail_url')}}"
                                              class="form-control"></div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">充值支付失败返回URL</label>

                <div class="col-sm-10"><input type="text" name="recharge_wechat_pay_fail_ucenter"
                                              value="{{settings('recharge_wechat_pay_fail_ucenter')}}"
                                              class="form-control"></div>
            </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存设置</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(function () {
        $('#setting_site_form').ajaxForm({
            success: function (result) {
                swal("保存成功!", "", "success")
            }
        });
    })
</script>