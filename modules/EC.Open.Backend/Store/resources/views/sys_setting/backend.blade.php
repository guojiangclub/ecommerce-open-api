{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>后台信息设置</h2>
    <ol class="breadcrumb">
        --}}{{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}{{--
        <li class="active">后台信息设置</li>
    </ol>
@endsection

@section('after-styles-end')
    --}}{{--@uploader('assets')--}}{{--
    <link href="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.css" rel="stylesheet">
@endsection

@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="tabs.html#tab-1" aria-expanded="true">基础信息</a></li>
            {{--<li class=""><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="false">通道设置</a></li>--}}
        </ul>
        <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
              id="setting_site_form">
            {{csrf_field()}}
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">后台页面Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="dmp_config[page_title]"
                                       value="{{$dmpConfig['page_title'] or ''}}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">后台系统简称</label>
                            <div class="col-sm-10">
                                <input type="text" name="dmp_config[short_title]"
                                       value="{{$dmpConfig['short_title'] or ''}}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">copyright</label>
                            <div class="col-sm-10">
                                <input type="text" name="dmp_config[copyright]"
                                       value="{{$dmpConfig['copyright'] or ''}}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">技术支持</label>
                            <div class="col-sm-10">
                                <input type="text" name="dmp_config[technical_support]"
                                       value="{{$dmpConfig['technical_support'] or ''}}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">后台登录页LOGO</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="dmp_config[login_logo]" value="{{$dmpConfig['login_logo'] or ''}}">
                                <img class="login_logo" src="{{$dmpConfig['login_logo'] or ''}}" alt="" style="max-width: 300px;">
                                <div id="videoPicker">选择图片</div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">后台LOGO</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="dmp_config[backend_logo]" value="{{$dmpConfig['backend_logo'] or ''}}">
                                <img class="backend_logo" src="{{$dmpConfig['backend_logo'] or ''}}" alt="" style="max-width: 300px;">
                                <div id="logoPicker">选择图片</div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否启用配置缓存：</label>

                            <div class="col-sm-10">
                                <label class="control-label">
                                    <input type="radio" value="1"
                                           name="dmp_config[setting-cache]" {{$dmpConfig['setting-cache'] ? 'checked': ''}}> 是
                                    &nbsp;&nbsp;
                                    <input type="radio" value="0"
                                           name="dmp_config[setting-cache]" {{!$dmpConfig['setting-cache'] ? 'checked': ''}}> 否
                                </label>
                            </div>
                        </div>

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label">是否启用管理员日志：</label>

                            <div class="col-sm-10">
                                <label class="control-label">
                                    <input type="radio" value="1"
                                           name="admin_databse_log_enabled" {{settings('admin_databse_log_enabled') ? 'checked': ''}}> 是
                                    &nbsp;&nbsp;
                                    <input type="radio" value="0"
                                           name="admin_databse_log_enabled" {{!settings('admin_databse_log_enabled') ? 'checked': ''}}> 否
                                </label>
                            </div>
                        </div>--}}

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label">后台页面Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="dmp_config[page_title]"
                                       value="{{$dmpConfig['page_title'] or ''}}"
                                       class="form-control">
                            </div>
                        </div>--}}

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label">验证码短信通用内容</label>
                            <div class="col-sm-10">
                                <input type="text" name="laravel-sms[dbLogs]"
                                       value="{{$smsSetting->dbLogs or ''}}"
                                       class="form-control">
                            </div>
                        </div>--}}
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        待完善
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

    <script src="{{env("APP_URL")}}/assets/backend/libs/jquery.form.min.js"></script>
    <script src="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.js"></script>--}}

    <script>
        $(function () {

            var uploader2 = WebUploader.create({
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('file.upload',['_token'=>csrf_token()])}}',
                pick: '#videoPicker',
                fileVal: 'file',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader2.on('uploadSuccess', function (file, response) {
                console.log(response);
                $('input[name="dmp_config[login_logo]"]').val(response.url);
                $('.login_logo').attr('src',response.url);
            });

            var uploader = WebUploader.create({
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('file.upload',['_token'=>csrf_token()])}}',
                pick: '#logoPicker',
                fileVal: 'file',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                $('input[name="dmp_config[backend_logo]"]').val(response.url);
                $('.backend_logo').attr('src',response.url);
            });

            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location.reload();
                    });
                }
            });
        })
    </script>
{{--@stop--}}