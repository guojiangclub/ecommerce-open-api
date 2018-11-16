{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员余额管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员余额管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.balances.list', '会员余额管理') !!}</li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.balances.list', '会员余额记录') !!}</li>--}}

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
            <li class="{{ Active::pattern('admin/member/balances') }}"><a href="{{route('admin.users.balances.list',['name'=>request('name'),'mobile'=>request('mobile'),'stime'=>request('stime'),'etime'=>request('etime')])}}"> 会员余额记录</a></li>

            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">搜索</a></li>
            <a data-toggle="modal"
               data-target="#modal" data-backdrop="static" data-keyboard="false"
               data-url="{{route('admin.users.balance.importBalance')}}"
               class="btn btn-w-m btn-info pull-right">批量导入余额</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    @include('member-backend::balance.includes.list')

                    <div class="pull-left">
                        @if(count($balances)>0)
                            当前{!! $balances->total() !!} 条记录
                        @endif
                    </div>

                    <div class="pull-right">
                        @if(count($balances)>0)
                            {!! $balances->appends(['name' => request('name'),'stime'=>request('stime'),'etime'=>request('etime')])->render() !!}
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            @include('member-backend::balance.includes.search-balance')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


{{--@stop--}}

<div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
@include('member-backend::balance.includes.script')