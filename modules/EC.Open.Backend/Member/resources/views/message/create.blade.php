{{--@extends ('member-backend::layout')--}}

{{--@section ('title',  '消息管理 | 发送消息')--}}


{{--@section('after-styles-end')--}}
        {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
{{--@stop--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>发送消息</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.RoleManagement.role.index')!!}"><i class="fa fa-dashboard"></i> 角色管理</a></li>--}}
        {{--<li class="active">发送消息</li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('content')--}}
    <div class="ibox float-e-margins">

        <div class="ibox-content" style="display: block;">
            {!! Form::open(['route' => 'admin.users.message.store', 'class' => 'form-horizontal',
            'role' => 'form', 'method' => 'post','id'=>'create-message-form']) !!}

            <div class="form-group">
                {!! Form::label('name', '消息内容：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' =>'','rows'=>4]) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('display_name', '接收用户：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    <div class="row">
                        <label class="checkbox-inline">
                            <input type="radio" checked value="all" name="group_type">
                            所有用户
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" value="users" name="group_type">
                            指定用户
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" value="groups" name="group_type">
                            指定分组
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" value="roles" name="group_type">
                            指定角色
                        </label>
                    </div>
                    <div style="margin-top: 20px"></div>
                    <div>
                        @include('member-backend::message.includes.group_type')
                    </div>

                </div>
            </div><!--form control-->


            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="发送"/>
                    <a href="{{route('admin.RoleManagement.role.index')}}" class="btn btn-danger">取消</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
{{--@stop--}}

{{--@section('after-scripts-end')--}}
    @include('member-backend::message.includes.script')
{{--@stop--}}


