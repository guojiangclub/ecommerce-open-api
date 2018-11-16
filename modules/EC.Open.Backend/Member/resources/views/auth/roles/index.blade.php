{{--@extends ('backend.layouts.master')--}}

{{--@section ('title', '角色管理')--}}

{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--用户管理--}}
        {{--<small>角色列表</small>--}}
    {{--</h1>--}}
{{--@endsection--}}

{{--@section ('breadcrumbs')--}}
    {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
    {{--<li>{!! link_to_route('admin.users.index', '用户管理') !!}</li>--}}
    {{--<li class="active">{!! link_to_route('admin.roles.index','角色列表') !!}</li>--}}
{{--@stop--}}

{{--@section('content')--}}
    @include('backend.auth.includes.header-buttons')

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>显示名称</th>
            <th>用户数量</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($roles as $role)
            <tr>
                <td>{!! $role->name !!}</td>
                <td>{!! $role->display_name !!}</td>
                <td>{!! $role->users()->count() !!}</td>
                <td>{!! $role->description !!}</td>
                <td>{!! $role->action_buttons !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pull-left">
        {{ $roles->total() }} 个角色
    </div>

    <div class="pull-right">
        {!!$roles->render()!!}
    </div>

    <div class="clearfix"></div>
{{--@stop--}}