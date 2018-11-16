{{--@extends ('member-backend::layout')--}}

{{--@section ('title',  '用户管理 | 创建用户')--}}


{{--@section('after-styles-end')--}}
    {{--{!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}--}}
{{--@stop--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>创建会员</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.users.index', '会员管理') !!}</li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.create', '创建会员') !!}</li>--}}
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
            {!! Form::open(['route' => 'admin.users.store', 'class' => 'form-horizontal',
            'role' => 'form', 'method' => 'post','id'=>'create-user-form']) !!}

            <div class="form-group">
                {!! Form::label('name', '昵称', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('nick_name',null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('mobile', '*手机号码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('password', '密码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('password_confirmation','确认密码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>
            </div><!--form control-->

            @if(count($groups)>0)
                <div class="form-group">
                    <label class="col-lg-2 control-label">会员等级<br/>
                    </label>
                    @foreach($groups as $key=> $item)
                        <div class="col-lg-2">
                            <input type="radio"   @if($key===0) checked="checked" @endif value="{{$item->id}}" name="group_id"/>{{$item->name}}
                        </div>
                    @endforeach
                </div><!--form control-->
            @endif


            <div class="form-group">
                <label class="col-lg-2 control-label">启用</label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="status" checked="checked"/>
                </div>
            </div><!--form control-->

            {{--<div class="form-group">
                <label class="col-lg-2 control-label">{{ trans('validation.attributes.confirmed') }}</label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="confirmed" checked="checked" />
                </div>
            </div><!--form control-->--}}

            <div class="form-group">
                <label class="col-lg-2 control-label">邮件激活<br/>

                </label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="confirmation_email"/>
                </div>
            </div><!--form control-->


            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
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
//                    name: {
//                        validators: {
//                            notEmpty: {
//                                message: '请输入用户名'
//                            }
//                        }
//                    },
                    mobile: {
                        validators: {
                            notEmpty: {
                                message: '请输入邮箱或手机号码'
                            },
                            regexp: {
                                regexp: /^1[3|4|5|7|8|9][0-9]{9}$/,
                                message: '手机号码格式错误'
                            }
                        }
                    },
                    email: {
                        enabled: false,
                        validators: {
                            notEmpty: {
                                message: '请输入邮箱或手机号码'
                            },
                            regexp: {
                                regexp:/^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/,
                                message: '邮箱格式不错误'
                            }
                        }
                    },
                    password: {
                        enabled: false,
                        validators: {
                            notEmpty: {
                                message: '请输入密码'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            identical: {
                                field: 'password',
                                message: '两次输入的密码不一致'
                            }
                        }
                    }
                }
            }).on('keyup', '[name="mobile"], [name="email"]', function (e) {
                var email = $('#create-user-form').find('[name="email"]').val(),
                        mobile = $('#create-user-form').find('[name="mobile"]').val(),

                        fv = $('#create-user-form').data('formValidation');

                switch ($(this).attr('name')) {
                        // User is focusing the mobile field
                    case 'mobile':
                        fv.enableFieldValidators('email', mobile === '').revalidateField('email');

                        if (mobile && fv.getOptions('mobile', null, 'enabled') === false) {
                            fv.enableFieldValidators('mobile', true).revalidateField('mobile');
                        } else if (mobile === '' && email !== '') {
                            fv.enableFieldValidators('mobile', false).revalidateField('mobile');
                        }
                        break;

                        // User is focusing the drivers license field
                    case 'email':
                        if (email === '') {
                            fv.enableFieldValidators('mobile', true).revalidateField('mobile');
                        } else if (mobile === '') {
                            fv.enableFieldValidators('mobile', false).revalidateField('mobile');
                        }

                        if (email && mobile === '' && fv.getOptions('email', null, 'enabled') === false) {
                            fv.enableFieldValidators('email', true).revalidateField('email');
                        }
                        break;

                    default:
                        break;
                }
            }).find('input[name="assignees_roles[]"]')
                    // Init icheck elements
                    /*.iCheck({
                        // The tap option is only available in v2.0
                        increaseArea: '20%',
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green'
                    })*/
                    // Called when the radios/checkboxes are changed
                    .on('ifChanged', function (e) {
                        // Get the field name
                        var field = $(this).attr('name');
                        $('#create-user-form').formValidation('revalidateField', field);
                    })
                    .end();
        });
    </script>
{{--@stop--}}
