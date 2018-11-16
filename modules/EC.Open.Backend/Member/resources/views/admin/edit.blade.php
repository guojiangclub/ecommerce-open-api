{{--@extends ('member-backend::layout')--}}
{{--@section ('title',  '管理员管理 | 编辑管理员')--}}


{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
{{--@stop--}}


{{--@section ('breadcrumbs')--}}
    {{--<h2>创建管理员</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.manager.index', '管理员管理') !!}</li>--}}
        {{--<li class="active">编辑管理员</li>--}}
    {{--</ol>--}}
{{--@stop--}}


{{--@section('content')--}}

    <div class="nav-tabs-custom tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

        <div class="tab-content div_1">
            {!! Form::model($user, ['route' => ['admin.manager.update', $user->id],
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'
            ,'id'=>'create-user-form']) !!}

            <div class="form-group">
                {!! Form::label('name', '账号', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name',null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->


            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('mobile', '手机', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    <input class="form-control" placeholder="" name="mobile" type="text" data-type="update" value="{{$user->mobile}}">
                </div>
            </div><!--form control-->

            {{--<div class="form-group ">--}}
                {{--{!! Form::label('password_confirmation','验证码', ['class' => 'col-lg-2 control-label']) !!}--}}

                {{--<div class="col-lg-10 input-group">--}}
                    {{--<input class="form-control" type="text"  required name="code">--}}
                    {{--<span class="input-group-btn"> <span data-target="edit"  data-status=0  class="btn btn-primary send-verifi">发送验证码--}}
                    {{--</span> </span>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="更新"/>
                    <a href="{{route('admin.users.index')}}" class="btn btn-danger">取消</a>
                </div>
            </div>


            {!! Form::close() !!}
        </div>



    </div>

{{--@stop--}}



{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/sms.js') !!}
    <script>
        $(document).ready(function () {
            $('#create-user-form').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: '请输入登录账号'
                            }
                        }
                    },
                    mobile: {
                        validators: {
                            notEmpty: {
                                message: '请输入手机号码'
                            }
                        }
                    },
                    code: {
                        validators: {
                            notEmpty: {
                                message: '请输入验证码'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: '请输入邮箱'
                            },
                            regexp: {
                                regexp:/^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/,
                                message: '邮箱格式不错误'
                            }
                        }
                    }
                }
            })
        });
    </script>
{{--@stop--}}