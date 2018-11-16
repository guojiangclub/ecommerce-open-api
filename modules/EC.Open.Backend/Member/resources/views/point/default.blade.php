{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员积分管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员积分管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.users.pointlist', '会员积分管理') !!}</li>--}}
        {{--<li class="active">{!! link_to_route('admin.member.points.default', '线上积分') !!}</li>--}}
    {{--</ol>--}}

{{--@stop--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}



{{--@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::pattern('admin/member/points') }}"><a href="{{route('admin.users.pointlist',['name'=>request('name'),'mobile'=>request('mobile'),'stime'=>request('stime'),'etime'=>request('etime')])}}"> 积分记录</a></li>
            <li class="{{ Active::pattern('admin/member/points/default') }}"><a href="{{route('admin.member.points.default',['name'=>request('name'),'mobile'=>request('mobile'),'stime'=>request('stime'),'etime'=>request('etime')])}}">线上积分</a></li>
            <li class="{{ Active::pattern('admin/member/points/offline') }}"><a href="{{route('admin.member.points.offline',['name'=>request('name'),'mobile'=>request('mobile'),'stime'=>request('stime'),'etime'=>request('etime')])}}">线下积分</a></li>
            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">搜索</a></li>
            {{--<a  id="chapter-create-btn" data-toggle="modal"--}}
            {{--data-target="#modal" data-backdrop="static" data-keyboard="false"--}}
            {{--data-url="{{route('admin.users.userexport')}}"--}}
            {{--class="btn btn-primary pull-right  " style="margin-right: 5px">导出</a>--}}
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    @include('member-backend::point.includes.points-list')

                    <div class="pull-left">
                        @if(count($points)>0)
                        当前{!! $points->total() !!} 条记录
                         @endif
                    </div>

                    <div class="pull-right">
                        @if(count($points)>0)
                            {!! $points->appends(['name' => request('name'),'stime'=>request('stime'),'etime'=>request('etime'),'mobile'=>request('mobile')])->render() !!}
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            @include('member-backend::point.includes.search-point')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
{{--@stop--}}


@include('member-backend::point.includes.script')
