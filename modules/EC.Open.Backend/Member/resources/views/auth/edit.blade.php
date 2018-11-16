{{--@extends ('member-backend::layout')--}}

{{--@section ('title', '会员管理 | 编辑会员' )--}}


{{--@section('after-styles-end')--}}
    {{--{!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}--}}
    {{--{!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}--}}
{{--@stop--}}
{{--
@section('page-header')
    <h1>
        用户管理
        <small>编辑用户</small>
    </h1>
@endsection--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>编辑会员</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.users.index', '会员管理') !!}</li>--}}
        {{--<li class="active">编辑会员</li>--}}
    {{--</ol>--}}

{{--@stop--}}


{{--@section('content')--}}
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
            <li><a href="#tab_2" class="tab_2" data-toggle="tab" aria-expanded="true">会员资料</a></li>
            <li><a href="#tab_3" class="tab_3" data-toggle="tab" aria-expanded="true">积分记录</a></li>
            <li><a href="#tab_4" class="tab_4" data-toggle="tab" aria-expanded="true">余额记录</a></li>
            {{--<li><a href="" class="tab_5" data-toggle="tab" aria-expanded="true">订单记录</a></li>--}}
            {{--@endif--}}
        </ul>

        <div class="tab-content">
            <div class="tab-pane active div_1" id="tab_1">
                <div class="panel-body">
                    {!! Form::model($user, ['route' => ['admin.users.update', $user->id],
                'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'
                ,'id'=>'create-user-form']) !!}

                    <div class="form-group">
                        {!! Form::label('avatar', '头像', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            <div class="pull-left" id="userAvatar">
                                <img src="{{!empty($user->avatar) ? $user->avatar : '/assets/backend/admin/img/no_head.png'}}"
                                     style="
                                         margin-right: 23px;
                                         width: 100px;
                                         height: 100px;
                                         border-radius: 50px;">
                                {!! Form::hidden('avatar', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="clearfix" style="padding-top: 22px;">
                                <div id="filePicker">添加图片</div>
                                <p style="color: #b6b3b3">温馨提示：图片尺寸建议为：250*250, 图片小于4M</p>
                            </div>
                        </div>
                    </div><!--form control-->

                    <div class="form-group">
                        {!! Form::label('name', '登录名', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                    </div><!--form control-->

                    <div class="form-group">
                        {!! Form::label('nick_name', '昵称', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('nick_name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
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
                            <input type="checkbox" value="1"
                                   name="status" {{$user->status == 1 ? 'checked' : ''}} />
                        </div>
                    </div><!--form control-->

                    <div class="form-group">
                        <label class="col-lg-2 control-label">邮件激活</label>
                        <div class="col-lg-1">
                            <input type="checkbox" value="1"
                                   name="confirmed" {{$user->confirmed == 1 ? 'checked' : ''}} />
                        </div>
                    </div><!--form control-->

                    <div class="form-group">
                        <label class="col-lg-2 control-label">角色</label>
                        <div class="col-lg-10">
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="{{$role->id}}"
                                               name="permissions[]" {{ $user->hasRole($role->name) ? 'checked' : ''}}>
                                        <i></i> {{$role->display_name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">分组</label>
                        <div class="col-lg-10">
                            @foreach($groups as $group)
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="{{$group->id}}"
                                               name="userGroups[]" {{ $user->hasGroup($group->name) ? 'checked' : ''}}>
                                        <i></i> {{$group->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>


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

            </div>

            <div class="tab-pane div_2" id="tab_2">
                <div class="panel-body">
                    {{--{!! Form::model($user, ['route' => ['admin.users.group_update', $user->id],--}}
                    {{--'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'--}}
                    {{--,'id'=>'create-user-form']) !!}--}}
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>登录名</td>
                                <td>{{$user->name}}</td>
                            </tr>
                            <tr>
                                <td>性别</td>
                                <td>{{$user->sex}}</td>
                            </tr>
                            <tr>
                                <td>昵称</td>
                                <td>{{$user->nick_name}}</td>
                            </tr>
                            <tr>
                                <td>总积分</td>
                                <td>{{$user->integral}}</td>
                            </tr>
                            <tr>
                                <td>可用积分</td>
                                <td>{{$user->available_integral}}</td>
                            </tr>
                            <tr>
                                <td>订单</td>
                                <td>{{$orderCount=count($user->hasManyOrders()->where('status','>',0)->get())}}笔
                                    @if($orderCount>0)
                                        <a href="{{route('admin.orders.index',['status'=>'all','user_id'=>$user->id])}}"
                                           target="_blank">点击查看</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>所属会员组</td>
                                <td>{{$user->group ? $user->group->name : ''}}</td>
                            </tr>
                            <tr>
                                <td>open_id</td>
                                <td>{{$user->open_id ? $user->open_id : ''}}</td>
                            </tr>
                            <tr>
                                <td>可用余额</td>
                                <td>{{$balance/100}}</td>
                            </tr>

                            <tr>
                                <td>出生日期</td>
                                <td>@if(!empty($user->birthday)) {{$user->birthday}} @else <b
                                            style="color: #7e7e7e">暂无</b> @endif</td>
                            </tr>
                            <tr>
                                <td>手机号码</td>
                                <td>@if(!empty($user->mobile)) {{$user->mobile}} @else <b
                                            style="color: #7e7e7e">暂无</b> @endif
                                </td>
                            </tr>
                            <tr>
                                <td>QQ</td>
                                <td>@if(!empty($user->qq)) {{$user->qq}} @else <b
                                            style="color: #7e7e7e">暂无</b> @endif
                                </td>
                            </tr>
                            <tr>
                                <td>邮箱</td>
                                <td>@if(!empty($user->email)) {{$user->email}} @else <b
                                            style="color: #7e7e7e">暂无</b> @endif
                                </td>
                            </tr>

                            <tr>
                                <td>上衣尺码</td>
                                <td>
                                    @if(isset($user->size->upper)&&!empty($user->size->upper))
                                        {{$user->size->upper}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>裤子尺码</td>
                                <td>
                                    @if(isset($user->size->lower)&&!empty($user->size->lower))
                                        {{$user->size->lower}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>鞋子尺码</td>
                                <td>
                                    @if(isset($user->size->shoes)&&!empty($user->size->shoes))
                                        {{$user->size->shoes}}
                                    @endif
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

            </div>

            <div class="tab-pane div_3" id="tab_3">
                <div class="panel-body">
                    <div class="box-body table-responsive no-padding integral">
                        @include('member-backend::auth.includes.user-integral-list')
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_4">
                <div class="panel-body">
                    <div class="box-body table-responsive no-padding">
                        @include('member-backend::auth.includes.user-balance-list')
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('store-backend::auth.script')
{{--@stop--}}



{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    {!! Html::script('assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}

    <script>
        $(document).ready(function () {
            $('#create-user-form').ajaxForm({
                success: function (result) {
                    if (!result.status) {
                        swal("保存失败!", result.message, "error")
                    } else {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location = decodeURIComponent('{{(isset($redirect_url) AND $redirect_url)?$redirect_url:route('admin.users.index')}}');
                        });
                    }
                }
            });

            // 初始化Web Uploader
            var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
            // 初始化Web Uploader
            var uploader = WebUploader.create({
                auto: true,
                swf: '{{url('assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.image',['_token'=>csrf_token()])}}',
                pick: '#filePicker',
                fileVal: 'upload_image',
                accept: {
                    title: 'Images',
                    extensions: 'jpg,jpeg,png',
                    mimeTypes: 'image/jpg,image/jpeg,image/png'
                }
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                $('#userAvatar img').attr("src", response.url);
                $('#userAvatar input').val(response.url);
            });

        });


    </script>

    @include('member-backend::auth.scripts.pointList-script')
    @include('member-backend::auth.scripts.balance-script')
{{--@stop--}}