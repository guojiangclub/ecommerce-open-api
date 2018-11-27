<div class="nav-tabs-custom tabs-container">
    @if (session()->has('flash_notification.message'))
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!! session('flash_notification.message') !!}
        </div>
    @endif
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" class="tab_1" data-toggle="tab" aria-expanded="true">基础信息</a></li>

        <li><a href="#tab_2" class="tab_2" data-toggle="tab" aria-expanded="true">会员资料</a></li>
        <li><a href="#tab_3" class="tab_3" data-toggle="tab" aria-expanded="true">积分记录</a></li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane active div_1" id="tab_1">
            <div class="panel-body">
                <input type="hidden" value="{{$user->id}}" name="user_id">
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
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td>性别</td>
                            <td>{{$user->sex}}</td>
                        </tr>
                        <tr>
                            <td>昵称</td>
                            <td>{{$user->nick_name}}</td>
                        </tr>
                        <tr>
                            <td>积分</td>
                            <td>{{$point}}</td>
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
                            <td>open_id</td>
                            <td>{{$user->bind->open_id}}</td>
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
                            <td>邮箱</td>
                            <td>@if(!empty($user->email)) {{$user->email}} @else <b
                                        style="color: #7e7e7e">暂无</b> @endif
                            </td>
                        </tr>


                        <tr>
                            <td>居住地</td>
                            <td>@if(!empty($user->province) && !empty($user->city)) {{$user->province}}{{$user->city}} @else
                                    <b style="color: #7e7e7e">暂无</b> @endif</td>
                        </tr>

                        <tr>
                            <td>注册时间</td>
                            <td>@if(!empty($user->created_at)) {{$user->created_at}} @else <b
                                        style="color: #7e7e7e"></b> @endif</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="tab-pane div_3" id="tab_3">
            <div class="panel-body">
                <div class="box-body table-responsive no-padding integral">
                    @include('member-backend::auth.includes.user-integral-list')
                </div>
            </div>
        </div>

    </div>

</div>

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