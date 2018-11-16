{{--@extends ('backend.layouts.master')--}}

{{--@section ('title', '角色管理 | 修改角色' )--}}

{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--角色管理--}}
        {{--<small>修改角色</small>--}}
    {{--</h1>--}}
{{--@endsection--}}

{{--@section ('breadcrumbs')--}}
    {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--<li>{!! link_to_route('admin.users.index', '用户管理') !!}</li>--}}
    {{--<li>{!! link_to_route('admin.roles.index', '角色管理') !!}</li>--}}
    {{--<li class="active">{!! link_to_route('admin.roles.edit', '编辑角色 ' . $role->name, $role->id) !!}</li>--}}
{{--@stop--}}

{{--@section('content')--}}
    @include('backend.auth.includes.header-buttons')

    {!! Form::model($role, ['route' => ['admin.roles.update', $role->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-role']) !!}

    <div class="form-group">
        {!! Form::label('name', '名称', ['class' => 'col-lg-2 control-label']) !!}
        <div class="col-lg-10">
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>
    </div><!--form control-->

    <div class="form-group">
        {!! Form::label('display_name', '显示名称', ['class' => 'col-lg-2 control-label']) !!}
        <div class="col-lg-10">
            {!! Form::text('display_name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>
    </div><!--form control-->

    <div class="form-group">
        {!! Form::label('description', '描述', ['class' => 'col-lg-2 control-label']) !!}
        <div class="col-lg-10">
            {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
        </div>
    </div><!--form control-->


    <div class="well">
        <div class="pull-left">
            <a href="{!! route('admin.roles.index') !!}" class="btn btn-danger btn-xs">取消</a>
        </div>

        <div class="pull-right">
            <input type="submit" class="btn btn-success btn-xs" value="保存"/>
        </div>
        <div class="clearfix"></div>
    </div><!--well-->

    {!! Form::close() !!}
{{--@stop--}}
