{{--@extends('member-backend::layout')--}}

{{--@section('title','会员列表')--}}

{{--@section('after-styles-end')--}}
        {{--<!-- 引入样式 -->--}}
        {!! Html::style(env("APP_URL").'/assets/backend/libs/element/index.css') !!}

{{--@stop--}}

{{--@section('breadcrumbs')--}}
    {{--<h2>{{$role->display_name}}角色用户管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.RoleManagement.role.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
        {{--<li><a href="{{route('admin.RoleManagement.role.index')}}">角色管理</a></li>--}}
        {{--<li class="active">角色用户管理</li>--}}
    {{--</ol>--}}
{{--@endsection--}}


{{--@section('content')--}}

    <div class="tabs-container">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body float-e-margins">

                    <div class="col-md-8">
                    <a class="btn btn-primary" id="users-btn" data-toggle="modal"
                       data-target="#users_modal" data-backdrop="static" data-keyboard="false"
                       data-url="{{route('admin.RoleManagement.role.userModal',['id'=>$role->id])}}">
                        添加会员
                    </a>

                    <a data-toggle="modal"
                       data-target="#users_modal" data-backdrop="static" data-keyboard="false"
                       data-url="{{route('admin.RoleManagement.role.importUser',['role_id'=>$role->id])}}"
                       class="btn btn-w-m btn-info" >批量导入会员</a>

                        <a class="btn btn-primary batchDel">
                            批量移除会员角色
                        </a>

                   </div>

                        <div class="col-md-4">
                            <form action="" method="get" class="form-horizontal">
                            <div class="input-group">
                                <input type="text" name="value" placeholder="手机"
                                       value="{{$value}}"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">查找</button> </span></div>
                            </form>
                        </div>


                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="check-all"></th>
                            <th>ID</th>
                            <th>会员名</th>
                            <th>邮箱</th>
                            <th>手机</th>
                            <th>积分</th>
                            <th>角色</th>
                            <th class="visible-lg">注册时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                            @if(count($users)>0&&$ids!='no')
                                @foreach ($users as $user)
                                    <tr class="user{{$user->id}}" uid="{{$user->id}}">
                                        <td><input type="checkbox" class="checkbox" value="{{$user->id}}" name="ids[]">
                                        <td>{!! $user->id !!}</td>
                                        <td>{!! $user->name !!}</td>
                                        <td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>
                                        <td>{!! $user->mobile !!}</td>
                                        <td>{!! $user->integral !!}</td>
                                        <td>{{$role->display_name}}</td>
                                        <td class="visible-lg">{!! $user->created_at !!}</td>
                                        <td>
                                            <a href="{{route('admin.users.edit', $user->id)}}" class="btn btn-xs btn-primary"><i
                                                        class="fa fa-pencil" data-toggle="tooltip" data-placement="top"
                                                        data-original-title="编辑"></i></a>

                                            <a href="javascript:;" data-url="{{route('admin.RoleManagement.role.allotDelRole',$role->id)}}"
                                               data-id="{{$user->id}}"
                                               class="btn btn-xs btn-danger operator"><i class="fa fa-times" data-toggle="tooltip"
                                                                                data-placement="top" title=""
                                                                                data-original-title="移除会员"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                    </table>
                    @if(count($users)>0&&$ids!='no')
                    <div class="pull-left">
                        {!! $users->total() !!} 个会员
                    </div>

                    <div class="pull-right">
                        {!! $users->render() !!}
                    </div>
                   @endif


                </div>
            </div>
        </div>
    </div>

    <div id="users_modal" class="modal inmodal fade" data-id="{{$role->id}}"></div>
    {{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
            <!-- 先引入 Vue -->
    {!! Html::script(env("APP_URL").'/assets/backend/libs/element/vue.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/element/index.js') !!}

    <script>
        $('.checkbox').on('ifChecked', function (event) {
            var val = $(this).val();
            $(this).parents('.user' + val).addClass('selected');
        });

        $('.checkbox').on('ifUnchecked', function (event) {
            var val = $(this).val();
            $(this).parents('.user' + val).removeClass('selected');
        });

        // 批量删除
        $('.batchDel').on('click', function () {
            if ($('.checkbox ').length <= 0) {
                swal("操作失败", "当前无可数据", "warning");
                return false;
            }
            var num = $('.selected').length;

            if (num == 0) {
                swal("注意", "请勾选需要操作的会员", "warning");
                return false;
            }

            var arr = [];
            for (var i = 0; i < num; i++) {
                var uid = $('.selected').eq(i).attr('uid');
                arr[i] = uid;
            }

            var url="{{route('admin.RoleManagement.role.allotDelRole',$role->id)}}"

            swal({
                title: "确定批量移除会员角色吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {uid: arr, _token: $('meta[name="_token"]').attr('content')},
                    success: function (date) {
                        if(date.status){
                            swal({
                                title: "移除成功",
                                text: "",
                                type: "success"
                            },function () {
                                parent.location.reload();
                            });
                        }
                    }
                });

            });





        });


        $('.operator').on('click',function () {
            var obj = $(this);
            swal({
                title: "确定将该会员从角色中移除吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                closeOnConfirm: false
            }, function () {
                var url = obj.data('url');
                var ids=[obj.data('id')];
                var data = {
                    uid: ids,
                    _token: $('meta[name="_token"]').attr('content')
                };

                $.post(url, data, function (ret) {
                    if (ret.status) {
                        swal({
                            title: "移除成功",
                            text: "",
                            type: "success"
                        },function () {
                            window.location.reload();
                        });
                    }
                });

            });
        });
    </script>
{{--@stop--}}