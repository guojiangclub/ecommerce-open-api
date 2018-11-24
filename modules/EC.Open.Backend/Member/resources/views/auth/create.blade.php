
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

            <div class="form-group">
                <label class="col-lg-2 control-label">启用</label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="status" checked="checked"/>
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
            });
        });
    </script>
