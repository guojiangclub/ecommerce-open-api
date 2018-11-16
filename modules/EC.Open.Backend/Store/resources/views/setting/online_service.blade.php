{{--@extends('store-backend::dashboard')

@section ('title','客服设置')

@section('breadcrumbs')
    <h2>客服设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">客服设置</li>
    </ol>
@endsection

@section('after-styles-end')
    <link href="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.css" rel="stylesheet">--}}
    <style type="text/css">
        .help-block {
            font-weight: normal;
            font-size: 12px;
            color: #a6aeb3
        }
    </style>
{{--@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveOnlineService')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="text-align: left">是否启用小程序客服：
                        <span class="help-block m-b-none">* 启用小程序客服后，会在小程序商品详情页显示在线客服的入口，目前仅支持芝麻客服系统，参考地址：<a
                                    href="http://xiaokefu.com.cn/index/index" target="_blank">
                                http://xiaokefu.com.cn/index/index
                            </a></span>
                    </label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="online_service_mini_status" {{settings('online_service_mini_status') ? 'checked': ''}}>
                            启用
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="online_service_mini_status" {{!settings('online_service_mini_status') ? 'checked': ''}}>
                            不启用
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" style="text-align: left">是否启用商城客服：
                        <span class="help-block m-b-none">* 启用客服后，会在商品详情页显示在线客服的入口</span>
                    </label>

                    <div class="col-sm-10">
                        <label class="control-label toggle-status">
                            <input type="radio" value="1"
                                   name="online_service_status" {{settings('online_service_status') ? 'checked': ''}}>
                            启用
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="online_service_status" {{!settings('online_service_status') ? 'checked': ''}}>
                            不启用
                        </label>
                    </div>
                </div>

                <div id="service_box" style="display: {{settings('online_service_status')?'block':'none'}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="text-align: left">客服设置：
                            <span class="help-block m-b-none">* 自有客服，需要上传客服微信二维码图片，客服电话以及客服在线时间</span>
                            <span class="help-block m-b-none">* 第三方云客服平台，需要自行去第三方客服平台获取在线客服的链接</span>
                        </label>

                        <div class="col-sm-10">
                            <label class="control-label toggle-type">
                                <input type="radio" value="self"
                                       name="online_service_type"
                                        {{(settings('online_service_type')=='self' OR !settings('online_service_type')) ? 'checked': ''}}>
                                自有客服
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" value="platform"
                                       name="online_service_type" {{settings('online_service_type')=='platform' ? 'checked': ''}}>
                                第三方云客服平台
                            </label>
                        </div>
                    </div>

                    <div id="service_self_box"
                         style="display: {{(settings('online_service_type')=='self' OR !settings('online_service_type'))?'block':'none'}}">
                        <div class="form-group"><label class="col-sm-2 control-label" style="text-align: left">客服电话:
                                <span class="help-block m-b-none">* 用户可直接在页面上面点击号码拨号</span>
                            </label>
                            <div class="col-sm-10"><input type="text" name="online_service_self[phone]"
                                                          placeholder="400-8888-8888"
                                                          class="form-control"
                                                          value="{{settings('online_service_self')?settings('online_service_self')['phone']:''}}">
                            </div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label"
                                                       style="text-align: left">客服工作时间:</label>
                            <div class="col-sm-10"><input type="text" name="online_service_self[time]"
                                                          placeholder="周一至周日：9:00 - 22:00" class="form-control"
                                                          value="{{settings('online_service_self')?settings('online_service_self')['time']:''}}">
                            </div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label" style="text-align: left">客服二维码:
                                <span class="help-block m-b-none">* 上传客服的微信二维码</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="hidden" name="online_service_self[qr_code]"
                                       value="{{settings('online_service_self')?settings('online_service_self')['qr_code']:''}}">
                                <img class="mobile_shop_qrcode"
                                     src="{{settings('online_service_self')?settings('online_service_self')['qr_code']:''}}"
                                     alt=""
                                     style="max-width: 100px;">
                                <div id="mobileShopPicker">选择图片</div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div id="service_platform_box"
                         style="display: {{settings('online_service_type')=='platform'?'block':'none'}}">
                        <div class="form-group"><label class="col-sm-2 control-label" style="text-align: left">云客服URL:
                                <span class="help-block m-b-none">* 目前推荐使用“平安客服云”，参考地址：<a
                                            href="http://kefu.pingan.com/index.html" target="_blank">
                                        http://kefu.pingan.com/index.html
                                    </a> </span>
                            </label>
                            <div class="col-sm-10"><input type="text" name="online_service_url"
                                                          placeholder="" class="form-control"
                                                          value="{{settings('online_service_url')}}"></div>
                        </div>
                    </div>
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



{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}--}}

    <script>
        $(function () {
            $(".toggle-status  input:radio").on('ifChecked', function (event) {
                var check = $(event.target).val();
                if (check == 0) {
                    $('#service_box').hide();
                } else {
                    $('#service_box').show();
                }
            });

            $(".toggle-type  input:radio").on('ifChecked', function (event) {
                var check = $(event.target).val();
                if (check == 'platform') {
                    $('#service_self_box').hide();
                    $('#service_platform_box').show();
                } else {
                    $('#service_self_box').show();
                    $('#service_platform_box').hide();
                }
            });

            var mobileShopPicker = WebUploader.create({
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('file.upload',['_token'=>csrf_token()])}}',
                pick: '#mobileShopPicker',
                fileVal: 'file',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            mobileShopPicker.on('uploadSuccess', function (file, response) {
                var img_url = response.url;
                $('input[name="online_service_self[qr_code]"]').val(AppUrl + img_url);
                $('.mobile_shop_qrcode').attr('src', img_url);
            });

            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    if (result.status) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal(result.message, '', 'error');
                    }

                }
            });
        });

    </script>
{{--
@stop--}}
