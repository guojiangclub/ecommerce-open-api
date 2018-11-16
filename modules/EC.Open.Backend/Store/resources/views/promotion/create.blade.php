@extends('store-backend::dashboard')

@section ('title','创建促销活动')

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
    <style type="text/css">
        table.category_table > tbody > tr > td{border: none}
    </style>
@stop


@section('breadcrumbs')
    <h2>新建促销</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">新建促销</li>
    </ol>
@endsection

@section('content')
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a aria-expanded="true" data-toggle="tab" href="#tab-1">基础信息</a></li>
            <li class=""><a aria-expanded="false" data-toggle="tab" href="#tab-2">详细配置</a></li>
        </ul>
        {!! Form::open( [ 'url' => [route('admin.promotion.store')], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.includes.base')
                    </fieldset>

                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.includes.configuration')
                    </fieldset>
                </div>
            </div>

        </div>

        <div class="hr-line-dashed"></div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">保存设置</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="spu_modal" class="modal inmodal fade"></div>
@stop

@section('after-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}

    @include('store-backend::promotion.includes.script')

    <script>
        $(function () {
            $('#create-discount-form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = '{{route('admin.promotion.index')}}';
                    });
                }
            });
        })
    </script>
@stop
