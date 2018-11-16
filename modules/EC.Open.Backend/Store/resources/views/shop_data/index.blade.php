{{--@extends('store-backend::dashboard')--}}

{{--@section ('title','商城数据统计')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}

{{--@stop--}}

{{--@section('breadcrumbs')--}}

    {{--<h2>商城数据统计</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">商城数据统计</li>--}}
    {{--</ol>--}}

{{--@endsection--}}

{{--@section('content')--}}

    <div class="tabs-container">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row form-horizontal">

                        <div class="col-md-8">
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;时间</span>
                                    <input type="text" class="form-control inline" name="stime"
                                           value="{{request('stime') ? request('stime'):''}}" placeholder="开始 "
                                           readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="etime"
                                           value="{{request('etime') ? request('etime'):''}}" placeholder="截止" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-primary" data-toggle="modal"
                               data-target="#modal" data-backdrop="static" data-keyboard="false"
                               data-link="{{route('admin.store.getExportData')}}" id="shop_data_xls"
                               data-url="{{route('admin.export.index',['toggle'=>'shop_data_xls'])}}"
                               data-type="xls"
                               href="javascript:;">导出</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal inmodal fade"></div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    @include('store-backend::shop_data.script')
{{--@stop--}}
