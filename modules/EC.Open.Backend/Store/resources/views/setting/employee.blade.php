{{--@extends('store-backend::dashboard')

@section ('title','员工内购设置')

@section('breadcrumbs')
    <h2>商城设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">员工内购</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveShopSetting')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">员工内购每月额度：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control"
                                   value="{{settings('employee_discount_limit')?settings('employee_discount_limit'):0}}"
                                   name="employee_discount_limit" placeholder="">
                        </label>
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

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });

                }
            });
        })
    </script>
{{--@stop--}}