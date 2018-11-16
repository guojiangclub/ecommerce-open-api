{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>Sentry 设置</h2>
    <ol class="breadcrumb">
        --}}{{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}{{--
        <li class="active">Sentry 设置</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="alert alert-danger">
                非专业人员请勿随意更改
            </div>
            <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
                  id="setting_site_form">

                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">是否启用Sentry：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="sentry_enabled" {{settings('sentry_enabled') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="sentry_enabled" {{!settings('sentry_enabled') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="form-group"><label class="col-sm-2 control-label">DSN</label>

                    <div class="col-sm-8">
                        <input type="text" name="sentry_dsn" value="{{settings('sentry_dsn')}}"
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