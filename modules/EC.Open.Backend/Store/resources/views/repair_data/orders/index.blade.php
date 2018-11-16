@extends('store-backend::dashboard')

@section ('title','订单列表')

@section('breadcrumbs')

    <h2>积分错漏订单列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">积分错漏订单列表</li>
    </ol>
@endsection

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop

@section('content')

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <button class="btn btn-primary" data-url="{{route('admin.repair.order.createPoint')}}" id="bitch-action">批量操作</button>

                    <div class="table-responsive">
                        <div id="orders">
                            @include('store-backend::repair_data.orders.includes.orders_list')
                        </div>
                    </div><!-- /.box-body -->

                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/sortable/Sortable.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}


    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
@stop

@include('store-backend::repair_data.orders.includes.script')


