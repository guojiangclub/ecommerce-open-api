@extends ('store-backend::dashboard')

@section ('title', '会员管理 | 编辑会员' )

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
@stop
{{--
@section('page-header')
    <h1>
        用户管理
        <small>编辑用户</small>
    </h1>
@endsection--}}

@section ('breadcrumbs')
    <h2>编辑会员</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li>{!! link_to_route('admin.users.index', '会员管理') !!}</li>
        <li class="active">编辑会员</li>
    </ol>

@stop


@section('content')
    {{--@include('backend.auth.includes.header-buttons')--}}
    <div class="nav-tabs-custom tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" class="tab_1" data-toggle="tab" aria-expanded="true">基础信息</a></li>
            {{--@if($user_roles[0]=='2')--}}
            <li><a href="" class="tab_2" data-toggle="tab" aria-expanded="true">会员资料</a></li>
            {{--<li><a href="" class="tab_3" data-toggle="tab" aria-expanded="true">积分记录</a></li>--}}
            {{--<li><a href="" class="tab_4" data-toggle="tab" aria-expanded="true">优惠券记录</a></li>--}}
            {{--<li><a href="" class="tab_5" data-toggle="tab" aria-expanded="true">订单记录</a></li>--}}
            {{--@endif--}}
        </ul>
        <div class="tab-content div_1">
            {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'
            ,'id'=>'create-user-form']) !!}

            <div class="form-group">
                {!! Form::label('name', '会员名', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('mobile', '手机号码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                <label class="col-lg-2 control-label">启用</label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="status" {{$user->status == 1 ? 'checked' : ''}} />
                </div>
            </div><!--form control-->

            <div class="form-group">
                <label class="col-lg-2 control-label">邮件激活</label>
                <div class="col-lg-1">
                    <input type="checkbox" value="1" name="confirmed" {{$user->confirmed == 1 ? 'checked' : ''}} />
                </div>
            </div><!--form control-->


            {{--<div class="form-group">--}}
                {{--<label class="col-lg-2 control-label">设置角色</label>--}}
                {{--<div class="col-lg-3">--}}
                    {{--@if (count($roles) > 0)--}}
                        {{--@foreach($roles as $role)--}}
                            {{--<input type="checkbox" value="{{$role->id}}" name="assignees_roles[]"--}}
                                   {{--{{in_array($role->id, $user_roles) ? 'checked' : ''}} id="role-{{$role->id}}"/>--}}
                            {{--<label--}}
                                    {{--for="role-{{$role->id}}">{!! $role->display_name !!}</label> <a href="#"--}}
                                                                                                    {{--data-role="role_{{$role->id}}"--}}
                                                                                                    {{--class="show-permissions small"></a>--}}
                            {{--<br/>--}}
                        {{--@endforeach--}}
                    {{--@else--}}
                        {{--系统不含任何角色--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div><!--form control-->--}}

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
        <div class="tab-content div_2" style="display: none">
            {{--{!! Form::model($user, ['route' => ['admin.users.group_update', $user->id],--}}
            {{--'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'--}}
            {{--,'id'=>'create-user-form']) !!}--}}
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td>会员名</td>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <td>可用积分</td>
                        <td>{{$user->integral}}</td>
                    </tr>
                    <tr>
                        <td>所属会员组</td>
                        <td>{{$user->group ? $user->group->name : ''}}</td>
                    </tr>
                    <tr>
                        <td>可用余额</td>
                        <td>{{$user->balance}}</td>
                    </tr>
                    <tr>
                        <td>出生日期</td>
                        <td>@if(!empty($user->birthday)) {{$user->birthday}} @else <b
                                    style="color: #7e7e7e">暂无</b> @endif</td>
                    </tr>
                    <tr>
                        <td>手机号码</td>
                        <td>@if(!empty($user->mobile)) {{$user->mobile}} @else <b style="color: #7e7e7e">暂无</b> @endif
                        </td>
                    </tr>
                    <tr>
                        <td>QQ</td>
                        <td>@if(!empty($user->qq)) {{$user->qq}} @else <b style="color: #7e7e7e">暂无</b> @endif</td>
                    </tr>
                    <tr>
                        <td>邮箱</td>
                        <td>@if(!empty($user->email)) {{$user->email}} @else <b style="color: #7e7e7e">暂无</b> @endif
                        </td>
                    </tr>
                    <tr>
                        <td>居住地</td>
                        <td>@if(!empty($user->province) && !empty($user->city)) {{$user->province}}{{$user->city}} @else
                                <b style="color: #7e7e7e">暂无</b> @endif</td>
                    </tr>
                    <tr>
                        <td>教育程度</td>
                        <td>@if(!empty($user->education)) {{$user->education}} @else <b
                                    style="color: #7e7e7e">暂无</b> @endif</td>
                    </tr>
                    <tr>
                        <td>注册时间</td>
                        <td>@if(!empty($user->created_at)) {{$user->created_at}} @else <b
                                    style="color: #7e7e7e"></b> @endif</td>
                    </tr>
                    {{--<tr>--}}
                        {{--<td>收货地址</td>--}}
                        {{--<td>--}}
                            {{--@if(count($user_address)>0)--}}
                                {{--<table class="table table-striped">--}}
                                    {{--<tbody>--}}
                                    {{--<tr>--}}
                                        {{--<th><b>姓名</b></th>--}}
                                        {{--<th><b>电话</b></th>--}}
                                        {{--<th><b>详细地址</b></th>--}}
                                    {{--</tr>--}}
                                    {{--@foreach($user_address as $user_addres)--}}
                                        {{--<tr>--}}
                                            {{--<td>{{$user_addres->accept_name}} </td>--}}
                                            {{--<td>{{$user_addres->mobile}}</td>--}}
                                            {{--<td>{{$user_addres->add_list}}</td>--}}
                                            {{--<td><span class="badge bg-red">55%</span></td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
                                    {{--</tbody>--}}
                                {{--</table>--}}
                            {{--@else--}}
                                {{--<b style="color: #7e7e7e">暂无收货地址</b>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                    </tbody>
                </table>
            </div>
            {{--{!! Form::close() !!}--}}
        </div>

        {{--<div class="tab-content div_3" style="display: none">--}}
            {{--<div class="box-body table-responsive no-padding integral">--}}
                {{--@include('backend.auth.includes.user-integral-list')--}}
            {{--</div>--}}
            {{--<div class="pull-right integrals">--}}
                {{--{!! $integral->render() !!}--}}
            {{--</div>--}}

        {{--</div>--}}


        {{--<div class="tab-content div_4" style="display: none">--}}
            {{--<div class="box-body table-responsive no-padding coupon" >--}}

                {{--@include('backend.auth.includes.user-coupons-list')--}}

            {{--</div>--}}

            {{--<div class="pull-right coupons">--}}
                {{--{!! $coupons->render() !!}--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="tab-content div_5" style="display: none">--}}
            {{--<div class="box-body table-responsive no-padding order">--}}

                {{--@include('backend.auth.includes.user-orders-list')--}}

            {{--</div>--}}

            {{--<div class="pull-right orders">--}}
                {{--{!! $orders->render() !!}--}}
            {{--</div>--}}
        {{--</div>--}}


    </div>
    @include('store-backend::auth.script')
@stop



@section('before-scripts-end')
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
                    name: {
                        validators: {
                            notEmpty: {
                                message: '请输入用户名'
                            }
                        }
                    }, mobile: {
                        validators: {
                            notEmpty: {
                                message: '请输入邮箱或手机号码'
                            },
                            regexp: {
                                regexp: /^1[3|4|5|7|8][0-9]{9}$/,
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
                                regexp:/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/,
                                message: '邮箱格式不错误'
                            }

                        }
                    },
                    'assignees_roles[]': {
                        validators: {
                            choice: {
                                min: 1,
                                max: 2,
                                message: '请选择用户所属系统角色'
                            }
                        }
                    }
                }
           })
                    .on('keyup', '[name="mobile"], [name="email"]', function (e) {
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
            // Called when the radios/checkboxes are changed
                    .on('ifChanged', function (e) {
                        // Get the field name
                        var field = $(this).attr('name');
                        $('#create-user-form').formValidation('revalidateField', field);
                    })
                    .end();

                    {{--.on('success.form.fv', function (e) {--}}
                        {{--// Prevent form submission--}}
                        {{--e.preventDefault();--}}

                        {{--// Get the form instance--}}
                        {{--var $form = $(e.target);--}}

                        {{--// Get the FormValidation instance--}}
                        {{--var bv = $form.data('formValidation');--}}

                        {{--// Use Ajax to submit form data--}}
                        {{--$.post($form.attr('action'), $form.serialize(), function(result) {--}}
                            {{--if(result.error){--}}
                                {{--swal({--}}
                                    {{--title: "更新失败！",--}}
                                    {{--text: result.error,--}}
                                    {{--type: "error"--}}
                                {{--}, function() {--}}
                                    {{--return location.reload();--}}
                                {{--});--}}
                            {{--}else{--}}
                                {{--return location.href ="{{route('admin.users.index')}}";--}}
                            {{--}--}}

                        {{--});--}}

                    {{--});--}}

        });



    </script>
@stop