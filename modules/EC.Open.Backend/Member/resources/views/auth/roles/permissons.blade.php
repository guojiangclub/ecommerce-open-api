{{--@extends ('backend.layouts.master')--}}

{{--@section ('title', '角色管理 | 角色权限' )--}}

{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--角色管理--}}
        {{--<small>角色权限</small>--}}
    {{--</h1>--}}
{{--@endsection--}}


{{--@section ('breadcrumbs')--}}
    {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--<li>{!! link_to_route('admin.users.index', '用户管理') !!}</li>--}}
    {{--<li>{!! link_to_route('admin.roles.index', '角色管理') !!}</li>--}}
    {{--<li class="active">{!! link_to_route('admin.roles.create','创建角色') !!}</li>--}}
{{--@stop--}}

{{--@section('content')--}}
    @include('backend.auth.includes.header-buttons')
         <span class="panel-title">编辑[<b>{{$role->display_name}}</b>]权限</span>

    {!! Form::open(['route' => ['admin.roles.permissionsadd',$role->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}
    <div class="well">
        @if($permissions->count()>0)
            <div class="form-group ">
                <div class="col-md-offset-1 col-md-10">
                    @foreach($permissions as $permission)
                        @if(starts_with($permission->name,'#'))
                            <div class="checkbox">
                                <label class="control-label">
                                    {{--@if(in_array($permission->id,array_keys($rolepermissions)))--}}
                                        {{--<input type="checkbox" name="permissions[]" value="{{$permission->id}}" class="am-field-valid" checked/>--}}
                                    {{--@else--}}
                                        {{--<input type="checkbox" name="permissions[]" value="{{$permission->id}}" class="am-field-valid"/>--}}
                                    {{--@endif--}}
                                    <span class="panel-title"><b>{{$permission->display_name}}</b></span></label>
                            </div>
                        @else
                                &nbsp;&nbsp;
                                    <div class="checkbox-inline">
                                        @if(in_array($permission->id,array_keys($rolepermissions)))
                                            <input  type="checkbox" value="{{$permission->id}}" name="permissions[]" id="role-{{$permission->id}}" checked/>
                                        @else
                                            <input  type="checkbox" value="{{$permission->id}}" name="permissions[]" id="role-{{$permission->id}}"/>
                                        @endif
                                        <label for="role-{{$permission->id}}">{!! $permission->display_name !!}</label> <a href="#"
                                                                                                       data-role="role_{{$permission->id}}" class="show-permissions small"></a>
                                    </div>
                                @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="well">

        <div class="pull-left">
            <a href="{{route('admin.roles.index')}}" class="btn btn-danger btn-xs">取消</a>
        </div>

        <div class="pull-right">
            <input type="submit" class="btn btn-success btn-xs" value="保存" />
        </div>

        <div class="clearfix"></div>
    </div><!--well-->
    {!! Form::close() !!}
{{--@stop--}}
