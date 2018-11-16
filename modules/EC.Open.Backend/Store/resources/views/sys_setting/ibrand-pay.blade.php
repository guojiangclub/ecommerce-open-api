{{--
@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>iBrand 支付设置</h2>
    <ol class="breadcrumb">
        --}}
{{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}{{--

        <li class="active">iBrand 支付设置</li>
    </ol>
@endsection

@section('content')
--}}

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="tabs.html#tab-1" aria-expanded="true">通用设置</a></li>
            <li class=""><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="true">微信支付</a></li>
            <li class=""><a data-toggle="tab" href="tabs.html#tab-3" aria-expanded="false">支付宝支付</a></li>
            <li class=""><a data-toggle="tab" href="tabs.html#tab-4" aria-expanded="false">小程序支付</a></li>
        </ul>
        <form method="post" action="{{route('admin.setting.pay')}}" class="form-horizontal"
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

                        <div class="form-group"><label class="col-sm-2 control-label">微信支付APPID</label>

                            <div class="col-sm-10"><input type="text" name="ibrand_wechat_pay_app_id"
                                                          value="{{settings('ibrand_wechat_pay_app_id')}}"
                                                          class="form-control"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">微信商户号MCHID</label>

                            <div class="col-sm-10"><input type="text" name="ibrand_wechat_pay_mch_id"
                                                          value="{{settings('ibrand_wechat_pay_mch_id')}}"
                                                          class="form-control"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">微信支付签名秘钥</label>

                            <div class="col-sm-10"><input type="text" name="ibrand_wechat_pay_key"
                                                          value="{{settings('ibrand_wechat_pay_key')}}"
                                                          class="form-control"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">微信支付异步通知url</label>

                            <div class="col-sm-10"><input type="text"
                                                          value="{{url('api/wechat_notify')}}"
                                                          class="form-control"></div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">apiclient_cert <i
                                        class="fa fa-question-circle"></i></label>
                            <div class="col-sm-10">
                            <textarea class="form-control" rows="4" name="ibrand_wechat_pay_apiclient_cert"></textarea>
                            </div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">apiclient_key <i
                                        class="fa fa-question-circle"></i></label>
                            <div class="col-sm-10">
                            <textarea class="form-control" rows="4" name="ibrand_wechat_pay_apiclient_key"></textarea>
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

                <div class="ibox-content m-b-sm border-bottom text-right">
                    <button class="btn btn-primary" type="submit">保存设置</button>
                </div>
            </div>

        </form>
    </div>


{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success")
                }
            });
        })
    </script>
{{--@stop--}}