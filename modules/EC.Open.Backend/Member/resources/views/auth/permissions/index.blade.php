{{--@extends ('backend.layouts.master')--}}

{{--@section ('title', '权限管理')--}}

{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--权限管理--}}
        {{--<small>权限列表</small>--}}
    {{--</h1>--}}
{{--@endsection--}}

{{--@section ('breadcrumbs')--}}
    {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
    {{--<li>{!! link_to_route('admin.users.index', '会员管理') !!}</li>--}}
    {{--<li class="active">{!! link_to_route('admin.permissions.index','权限列表') !!}</li>--}}
{{--@stop--}}

{{--@section('content')--}}
    @include('backend.auth.includes.header-buttons')

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>路由</th>
            <th>显示名称</th>
            <th>角色数量</th>
            <th>说明</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($permissions as $permission)
            <tr>
                <td>{!! $permission->id !!}</td>
                <td>{!! $permission->name !!}</td>
                <td>{!! $permission->display_name !!}</td>
                <td>{!! $permission->roles()->count() !!}</td>
                <td>{!! $permission->description !!}</td>
                <td>{!! $permission->action_buttons !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pull-left">
        {!!$permissions->total()!!}个权限
    </div>

    <div class="pull-right">
        {!!$permissions->render()!!}
    </div>

    <div class="clearfix"></div>
{{--@stop--}}