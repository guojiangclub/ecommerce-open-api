{{--@extends ('backend.layouts.master')--}}

{{--@section ('title', '权限管理 | 修改权限' )--}}

{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--权限管理--}}
        {{--<small>修改权限</small>--}}
    {{--</h1>--}}
{{--@endsection--}}

{{--@section ('breadcrumbs')--}}
    {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--<li>{!! link_to_route('admin.permissions.index', '会员管理') !!}</li>--}}
    {{--<li>{!! link_to_route('admin.permissions.index', '权限管理') !!}</li>--}}
    {{--<li class="active">{!! link_to_route('admin.permissions.edit', '编辑权限 ' . $permission->name,$permission->id) !!}</li>--}}
{{--@stop--}}

{{--@section('content')--}}
    @include('backend.auth.includes.header-buttons')

    {!! Form::model($permission, ['route' => ['admin.permissions.update', $permission->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-role']) !!}

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
            <a href="{!! route('admin.permissions.index') !!}" class="btn btn-danger btn-xs">取消</a>
        </div>

        <div class="pull-right">
            <input type="submit" class="btn btn-success btn-xs" value="保存"/>
        </div>
        <div class="clearfix"></div>
    </div><!--well-->

    {!! Form::close() !!}
{{--@stop--}}
