@extends ('store-backend::dashboard')

@section ('title','用户管理')

@section ('breadcrumbs')
    <h2>会员管理</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">{!! link_to_route('admin.users.index', '会员管理') !!}</li>
    </ol>
@stop



@section('content')
    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif
        <ul class="nav nav-tabs">
            <li class="{{ Active::pattern('admin/users') }}"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 会员列表</a></li>
            <li class="{{ Active::pattern('admin/users/banned') }}"><a href="{{route('admin.users.banned')}}">禁用的会员</a></li>
            <li class="{{ Active::pattern('admin/users/deleted') }}"><a href="{{route('admin.users.deleted')}}">删除的会员</a></li>
            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">搜索</a></li>
            <a href="{{route('admin.users.create')}}" class="btn btn-w-m btn-info pull-right">添加会员</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {{--@include('backend.auth.includes.header-buttons')--}}

                    @include('store-backend::auth.includes.user-list')

                    <div class="pull-left">
                        {!! $users->total() !!} 个会员
                    </div>

                    <div class="pull-right">
                        {!! $users->render() !!}
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            @include('store-backend::auth.includes.search-user')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop