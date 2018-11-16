{{--@extends ('member-backend::layout')--}}

{{--@section('title','角色管理')--}}

{{--@section('breadcrumbs')--}}
    {{--<h2>角色管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.RoleManagement.role.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
        {{--<li class="active">角色管理</li>--}}
    {{--</ol>--}}
{{--@endsection--}}

{{--@section('after-styles-end')--}}
    {{--<!-- 引入样式 -->--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/element/index.css') !!}
{{--@stop--}}


{{--@section('content')--}}

    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">角色列表</a></li>
            <a href="{!!route('admin.RoleManagement.role.create')!!}" class="btn btn-w-m btn-info pull-right">添加角色</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>角色名称</th>
                            <th>显示名称</th>
                            <th>描述</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($models as $model)
                            <tr>
                                <td>{!! $model->id !!}</td>
                                <td>{!! $model->name !!}</td>
                                <td>{!! $model->display_name !!}</td>
                                <td>
                                    {!! $model->description !!}
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-primary" href="{{route('admin.RoleManagement.role.edit',['id'=>$model->id])}}">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o" title="" data-original-title="编辑"></i></a>

                                    <a class="btn btn-xs btn-primary" href="{{route('admin.RoleManagement.role.userList',['id'=>$model->id])}}">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-user-md" title="" data-original-title="会员列表"></i></a>

                                    <a class="btn btn-xs btn-primary" id="users-btn" data-toggle="modal"
                                       data-target="#users_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.RoleManagement.role.userModal',['id'=>$model->id])}}">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-gears" title="" data-original-title="批量分配会员角色" ></i>
                                    </a>

                                    <a  href="javascript:;"  class="btn btn-xs btn-danger delete"
                                       data-href="{{route('admin.RoleManagement.role.delete', $model->id)}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                    {{ $models->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="users_modal" class="modal inmodal fade" data-id="{{request('id')}}" ></div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    <!-- 先引入 Vue -->
    {!! Html::script(env("APP_URL").'/assets/backend/libs/element/vue.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/element/index.js') !!}
{{--@stop--}}


<script>
    $('.delete').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要删除吗?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.RoleManagement.role.index')}}';
                    });
                } else {
                    swal({
                        title: result.message,
                        text: "",
                        type: "warning"
                    });
                }
            });
        });
    });
</script>