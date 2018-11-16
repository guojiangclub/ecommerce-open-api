{{--@extends ('member-backend::layout')--}}

{{--@section ('title','管理员管理')--}}


{{--@section ('breadcrumbs')--}}
    {{--<h2>管理员管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.index', '会员管理') !!}</li>--}}
    {{--</ol>--}}
{{--@stop--}}



{{--@section('content')--}}
    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 管理列表</a></li>
                <a href="{{route('admin.manager.create')}}" class="btn btn-w-m btn-info pull-right">添加管理员</a>
                <a href="{{route('admin.manager.loginLog')}}" style="margin-right: 10px" class="btn btn-w-m btn-warning pull-right">查看登录日志</a>
            </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>账号</th>
                            <th>邮箱</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{!! $user->id !!}</td>
                                <td>{!! $user->name !!}</td>
                                <td>{!! $user->email !!}</td>
                                <td>
                                    <a href="{{route('admin.manager.edit',['id' => $user->id])}}" class="btn btn-xs btn-primary">
                                        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"></i></a>

                                    <a href="{{route('admin.manager.changePassword', ['id' => $user->id])}}" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="修改密码"></i></a>

                                    <a href="{{route('admin.manager.delete',['id' => $user->id])}}" data-method="delete" class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除用户"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{--<div class="pull-left">--}}
                        {{--{!! $users->total() !!} 个会员--}}
                    {{--</div>--}}

                    {{--<div class="pull-right">--}}
                        {{--{!! $users->render() !!}--}}
                    {{--</div>--}}

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
{{--@stop--}}