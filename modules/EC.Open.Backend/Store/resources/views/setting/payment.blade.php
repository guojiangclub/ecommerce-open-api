<style>
    .wecaht_pay_txt {
        font-size: 16px;
        font-weight: bold;
    }

    .textarea_disabled {
        background-color: #F3F3F3;
    }

    .text-adaption {
        height: 34px;
        overflow: hidden;
        padding: 5px 10px;
        resize: none;
        line-height: 24px;
        font-size: 12px;
        color: #666;
        border: 1px solid #ccc;
        outline: 0 none;
        border-radius: 3px;
        box-sizing: border-box;
    }
</style>

<div class="tabs-container">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="tabs.html#tab-1" aria-expanded="true">通用设置</a></li>
        <li class=""><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="true">微信支付</a></li>
        <li class=""><a data-toggle="tab" href="tabs.html#tab-3" aria-expanded="false">支付宝支付</a></li>
        <li class=""><a data-toggle="tab" href="tabs.html#tab-4" aria-expanded="false">小程序支付</a></li>

    </ul>
    <form method="post" action="{{route('admin.setting.savePaymentSetting')}}" class="form-horizontal"
          id="setting_site_form">
        {{csrf_field()}}
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="form-group"><label class="col-sm-2 control-label">支付场景 <i
                                    class="fa fa-question-circle"></i></label>
                        <div class="col-sm-10">
                            <input type="radio" value="live" name="pay_scene"
                                    {{settings('pay_scene') == 'live'? 'checked':''}}> live &nbsp;&nbsp;
                            <input type="radio" value="test" name="pay_scene"
                                    {{settings('pay_scene') == 'test'? 'checked':''}}> test
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">

                    <div style="margin-bottom:40px;">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-6" style="display:flex;align-items: center;">
                                <span>
                                    <svg t="1531469326325" class="icon" style="" viewBox="0 0 1024 1024" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" p-id="1131"
                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25">
                                        <defs>
                                            <style type="text/css"></style>
                                        </defs>
                                        <path d="M404.511405 600.865957c-4.042059 2.043542-8.602935 3.223415-13.447267 3.223415-11.197016 0-20.934798-6.169513-26.045189-15.278985l-1.959631-4.296863-81.56569-178.973184c-0.880043-1.954515-1.430582-4.14746-1.430582-6.285147 0-8.251941 6.686283-14.944364 14.938224-14.944364 3.351328 0 6.441713 1.108241 8.94165 2.966565l96.242971 68.521606c7.037277 4.609994 15.433504 7.305383 24.464181 7.305383 5.40101 0 10.533914-1.00284 15.328104-2.75167l452.645171-201.459315C811.496653 163.274644 677.866167 100.777241 526.648117 100.777241c-247.448742 0-448.035176 167.158091-448.035176 373.361453 0 112.511493 60.353576 213.775828 154.808832 282.214547 7.582699 5.405103 12.537548 14.292518 12.537548 24.325012 0 3.312442-0.712221 6.358825-1.569752 9.515724-7.544837 28.15013-19.62599 73.202209-20.188808 75.314313-0.940418 3.529383-2.416026 7.220449-2.416026 10.917654 0 8.245801 6.692423 14.933107 14.944364 14.933107 3.251044 0 5.89015-1.202385 8.629541-2.7793l98.085946-56.621579c7.377014-4.266164 15.188934-6.89913 23.790846-6.89913 4.577249 0 9.003048 0.703011 13.174044 1.978051 45.75509 13.159718 95.123474 20.476357 146.239666 20.476357 247.438509 0 448.042339-167.162184 448.042339-373.372709 0-62.451354-18.502399-121.275087-51.033303-173.009356L407.778822 598.977957 404.511405 600.865957z"
                                              p-id="1132" fill="#41b035"></path>
                                    </svg>
                                </span>
                                <span class="wecaht_pay_txt" style="color:#4A4A4A;margin-left: 8px;">微信公众号支付：</span>
                                <span class="wecaht_pay_txt" style="color: #2BC0BE;">已开通</span>
                                {{--<span class="wecaht_pay_txt" style="color: red;">未开通</span>--}}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            {{--<div class="col-sm-6">
                                <a href="{{route('admin.setting.paymentSetting',['type'=>'wechat_pay','action'=>'edit'])}}"
                                   type="button" style="color:#4B8AF2;border-color:#4B8AF2 " class="btn">修改参数</a>
                                <a type="button" style="color:#4B8AF2;border-color:#4B8AF2 " class="btn">查看参数填写步骤</a>
                            </div>--}}
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">渠道费率：</div>

                        <div class="col-sm-3">*请在此处填写你的道签约费率，以便我们在报表中帮你计算结算金额。若不清楚签约费率，请联系支付渠道确认（目前暂不支持阶梯费率计算）</div>

                        <div class="col-sm-5">
                            <textarea class="form-control"  rows="1"></textarea>
                        </div>

                    </div>


                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">公众号APPID：</div>

                        <div class="col-sm-3">*微信公众平台开发基本配置－开发者ID APPID</div>

                        <div class="col-sm-5">

                            <textarea class="form-control"  rows="1"
                                      name="wechat_pay_app_id">{{settings('wechat_pay_app_id')}}</textarea>

                        </div>

                    </div>


                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">微信支付商户号：</div>

                        <div class="col-sm-3">*在微信支付中请完成后微信支付商户平台的通知邮件中获取，请确保邮件中的公众号PpD与本次填写的公众号 APPID一致</div>

                        <div class="col-sm-5">

                            <textarea class="form-control"  rows="1"
                                      name="ibrand_wechat_pay_mch_id">{{settings('ibrand_wechat_pay_mch_id')}}</textarea>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">API密钥：</div>

                        <div class="col-sm-3">**微信支付商户平台一账户中心影户设理AP安全A密钥设置密钥</div>

                        <div class="col-sm-5">

                            <textarea class="form-control"  rows="1"
                                      name="ibrand_wechat_pay_key">{{settings('ibrand_wechat_pay_key')}}</textarea>

                        </div>

                    </div>


                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">apiclient_cert:</div>

                        <div class="col-sm-3"></div>

                        <div class="col-sm-5">

                            <textarea class="form-control"  rows="4"
                                      name="ibrand_wechat_pay_apiclient_cert">{{settings('ibrand_wechat_pay_apiclient_cert')}}</textarea>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">apiclient_key:</div>

                        <div class="col-sm-3"></div>

                        <div class="col-sm-5">

                            <textarea class="form-control"  rows="4"
                                      name="ibrand_wechat_pay_apiclient_key">{{settings('ibrand_wechat_pay_apiclient_key')}}</textarea>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-sm-1"></div>

                        <div class="col-sm-1">员工登录账号：</div>

                        <div class="col-sm-3">*微信支付商户平台员工账号管理一新增账号／倍理账号登录账号用于退款及退款查询询一请填写拥有中请退款、温款查询权限的角色账号</div>

                        <div class="col-sm-5">

                            <textarea class="form-control" rows="1"></textarea>

                        </div>

                    </div>


                </div>

            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    <div class="form-group"><label class="col-sm-2 control-label">支付宝支付APPID</label>

                        <div class="col-sm-10"><input type="text" name="ibrand_alipay_app_id"
                                                      value="{{settings('ibrand_alipay_app_id')}}"
                                                      class="form-control"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">支付宝异步通知url </label>

                        <div class="col-sm-10"><input type="text"
                                                      value="{{url('api/ali_notify')}}"
                                                      class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">支付宝同步通知url </label>

                        <div class="col-sm-10"><input type="text"
                                                      value="{{url('api/ali_return')}}"
                                                      class="form-control"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">支付宝公共密钥 <i
                                    class="fa fa-question-circle"></i></label>
                        <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="ibrand_alipay_ali_public_key">{{settings('ibrand_alipay_ali_public_key')}}</textarea>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">支付宝商户私钥 <i
                                    class="fa fa-question-circle"></i></label>
                        <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="ibrand_alipay_private_key"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-4" class="tab-pane">
                <div class="panel-body">

                    <div class="form-group"><label class="col-sm-2 control-label">小程序支付APPID</label>

                        <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_miniapp_id"
                                                      value="{{settings('ibrand_miniapp_pay_miniapp_id')}}"
                                                      class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">小程序商户号MCHID</label>

                        <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_mch_id"
                                                      value="{{settings('ibrand_miniapp_pay_mch_id')}}"
                                                      class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">小程序支付签名秘钥</label>

                        <div class="col-sm-10"><input type="text" name="ibrand_miniapp_pay_key"
                                                      value="{{settings('ibrand_miniapp_pay_key')}}"
                                                      class="form-control"></div>
                    </div>


                    <div class="form-group"><label class="col-sm-2 control-label">小程序支付异步通知url</label>

                        <div class="col-sm-10"><input type="text"
                                                      value="{{url('api/wechat_notify')}}"
                                                      class="form-control"></div>
                    </div>
                </div>
            </div>
        </div>

        {{--@if(request('action')=='edit')--}}
            <div class="form-group" style=" margin-top: 15px">
                <div class="col-sm-4 col-sm-offset-2">
                    <button id="submit_pay" class="btn btn-primary" type="submit">保存设置</button>
                </div>
            </div>
        {{--@endif--}}

    </form>
</div>


<script>
    $(function () {
        var obj = $('.text-adaption');
        var len = obj.length;
        for (var i = 0; i < len; i++) {
            //console.log(obj[i].value);
            obj[i].onkeyup = function () {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + "px";
            };
        }

        var submit_pay = $('#submit_pay');
        if (submit_pay.length > 0) {
            obj.removeClass('textarea_disabled');
            obj.removeAttr('disabled');
        }

    });

    $('#setting_site_form').ajaxForm({
        success: function (result) {
            swal({
                title: "保存成功！",
                text: "",
                type: "success"
            }, function () {
                location.href = "{{route('admin.setting.paymentSetting',['type'=>'wechat_pay'])}}";
            });
        }
    });

</script>
