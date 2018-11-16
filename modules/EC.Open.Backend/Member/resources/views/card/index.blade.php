{{--@extends('member-backend::layout')--}}

{{--@section ('title','会员卡列表')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}

{{--@stop--}}

{{--@section('breadcrumbs')--}}

    {{--<h2>会员卡列表</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">会员卡列表</li>--}}
    {{--</ol>--}}

{{--@endsection--}}

{{--@section('content')--}}

    {{--@if(Session::has('message'))--}}
        {{--<div class="alert alert-success alert-dismissable">--}}
            {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i> 提示！</h4>--}}
            {{--{{ Session::get('message') }}--}}
        {{--</div>--}}
    {{--@endif--}}

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="{{route('admin.card.index')}}">所有会员卡
                    <span class="badge"></span></a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        {!! Form::open( [ 'route' => ['admin.card.index'], 'method' => 'get', 'class'=>'form-horizontal'] ) !!}
                        <div class="col-md-6">
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;时间</span>
                                    <input  type="text" class="form-control inline" name="stime" id="stime"  value="{{request('stime')?request('stime'):''}}"   placeholder="开始 " readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="etime" id="etime"   value="{{request('etime')?request('etime'):''}}"    placeholder="截止" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>

                        </div>



                        <div class="col-md-2">
                            <select class="form-control" name="field">
                                <option value="number"
                                @if(request('field')=='number')
                                selected
                                @endif>
                                    卡号</option>
                                {{--<option value="name" @if(request('field')=='name')--}}
                                {{--selected--}}
                                        {{--@endif> 姓名</option>--}}
                                <option value="mobile"  @if(request('field')=='mobile')
                                selected
                                        @endif>手机</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" name="value" placeholder="Search" value="{{request('value')?request('value'):''}}"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown"
                               href="javascript:;" data-style="zoom-in">导出<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a  data-toggle="modal"
                                   data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                   data-link="{{route('admin.card.getExportData',['type'=>'xls'])}}" id="all-xls"
                                   data-url="{{route('admin.export.index',['toggle'=>'all-xls'])}}"
                                   data-type="xls"
                                   href="javascript:;">导出所有会员卡</a></li>

                                <li><a data-toggle="modal-filter"
                                       data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                       id="filter-xls"
                                       data-url="{{route('admin.export.index',['toggle'=>'filter-xls'])}}"
                                       data-type="xls"
                                       href="javascript:;">导出筛选结果会员卡</a></li>

                            </ul>
                        </div>
                        {!! Form::close() !!}
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>会员卡号</th>
                                <th>姓名</th>
                                <th>手机号码</th>
                                <th>生日</th>
                                <th>申领时间</th>
                                <th>操作</th>
                            </tr>
                            <!--tr-th end-->

                            @foreach ($cardList as $item)
                                <tr>
                                    <td>NO.{{$item->number}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->mobile}}</td>
                                    <td>{{$item->birthday}}</td>
                                    <th>{{$item->created_at}}</th>
                                    <td>
                                        @if(env('CUSTOMIZATION'))
                                            <a
                                                    class="btn btn-xs btn-primary"
                                                    href="{{route('admin.card.edit',['id'=>$item->id])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6" class="footable-visible">
                                    {!! $cardList->render() !!}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>

    <div id="download_modal" class="modal inmodal fade"></div>
{{--@endsection--}}

{{--@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
    @include('member-backend::card.script')
  {{--@stop--}}