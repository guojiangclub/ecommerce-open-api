{{--@extends ('member-backend::layout')--}}

{{--@section ('title',  '角色管理 | 编辑角色')--}}


{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
{{--@stop--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>编辑角色</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.RoleManagement.role.index')!!}"><i class="fa fa-dashboard"></i> 角色管理</a></li>--}}
        {{--<li class="active">编辑角色</li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('content')--}}
    {{--@include('backend.auth.includes.header-buttons')--}}
    <div class="ibox float-e-margins">
            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('flash_notification.message') !!}
                </div>
            @endif
        <div class="ibox-content" style="display: block;">
            {!! Form::model($model, ['url' => route('admin.RoleManagement.role.update', $model->id), 'class' => 'form-horizontal',
            'role' => 'form', 'method' => 'PATCH','id'=>'create-roleManagement-form']) !!}

            <div class="form-group">
                {!! Form::label('name', '*角色名称', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name',null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('display_name', '*显示名称', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('display_name', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('description', '描述', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            {{--<div class="form-group">--}}
                {{--{!! Form::label('role-permission', '角色权限:', ['class' => 'col-lg-2 control-label']) !!}--}}
                {{--<div class="col-lg-10">--}}
                    {{--@foreach($permissionModels as $permissionModel)--}}
                     {{--<div class="col-lg-2">--}}
                        {{--<label class="control-label">{{$permissionModel->display_name}}:</label>--}}
                        {{--<input type="checkbox" value="{{$permissionModel->id}}" name="permissions[]" {{ $model->hasPermission($permissionModel->name) ? 'checked' : ''}} />--}}
                    {{--</div>--}}
                     {{--@endforeach--}}
                {{--</div>--}}
            {{--</div><!--form control-->--}}




            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
                    <a href="{{route('admin.RoleManagement.role.index')}}" class="btn btn-danger">取消</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
{{--@stop--}}

{{--@section('after-scripts-end')--}}
    <script>
        $('#create-roleManagement-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.error, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{!!route('admin.RoleManagement.role.index')!!}';
                    });
                }

            }

        });

    </script>

{{--@stop--}}


