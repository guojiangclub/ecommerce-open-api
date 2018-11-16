@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>加密设置</h2>
    <ol class="breadcrumb">
        {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
        <li class="active">加密设置</li>
    </ol>
@endsection


@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码加密方式：</label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline i-checks">
                            <input name="password_encryption_type" type="radio" value="bcrypt"
                                    {{settings('password_encryption_type') == 'bcrypt'? 'checked':''}} > bcrypt</label>
                        <label class="checkbox-inline i-checks">
                            <input name="password_encryption_type" type="radio" value="SHA256"
                                    {{settings('password_encryption_type') == 'SHA256'? 'checked':''}}> SHA256</label>
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


@endsection

@section('after-scripts-end')

    <script src="{{env("APP_URL")}}/assets/backend/libs/jquery.form.min.js"></script>
    <script src="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.js"></script>

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success")
                }
            });
        })
    </script>
@stop