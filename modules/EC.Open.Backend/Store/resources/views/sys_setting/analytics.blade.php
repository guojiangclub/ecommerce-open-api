{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>网站统计配置</h2>
    <ol class="breadcrumb">
        --}}{{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}{{--
        <li class="active">网站统计配置</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
                  id="setting_site_form">

                {{csrf_field()}}

                <div class="form-group"><label class="col-sm-3 control-label">域名</label>

                    <div class="col-sm-8">
                        <input type="text" name="analytics_domain" value="{{settings('analytics_domain')}}"
                               class="form-control"></div>
                </div>


                <div class="form-group"><label class="col-sm-3 control-label">baidu</label>

                    <div class="col-sm-8">
                        <input type="text" name="analytics_baidu_id" value="{{settings('analytics_baidu_id')}}"
                               class="form-control"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">google analytics</label>

                    <div class="col-sm-8">
                        <input type="text" name="analytics_google_ua_id" value="{{settings('analytics_google_ua_id')}}"
                               class="form-control"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">tencent</label>

                    <div class="col-sm-8">
                        <input type="text" name="analytics_tencent_id" value="{{settings('analytics_tencent_id')}}"
                               class="form-control"></div>
                </div>

                {{--<div class="form-group"><label class="col-sm-3 control-label">adobe DTM header code</label>

                    <div class="col-sm-8">
                        <input type="text" name="adobe_dtm_header_code"  value="{{settings('adobe_dtm_header_code')}}"
                               class="form-control"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">adobe DTM footer code</label>

                    <div class="col-sm-8">
                        <input type="text" name="adobe_dtm_footer_code" value="{{settings('adobe_dtm_footer_code')}}"
                               class="form-control"></div>
                </div>


                <div class="form-group"><label class="col-sm-3 control-label">ptmind ptengine</label>

                    <div class="col-sm-8">
                        <textarea name="ptmind_ptengine" rows="3" class="form-control">{{settings('ptmind_ptengine')}}</textarea>
                    </div>
                </div>--}}

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