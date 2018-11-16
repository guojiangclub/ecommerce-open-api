{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员分组管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员分组管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.group.list', '会员分组管理') !!}</li>--}}
    {{--</ol>--}}

{{--@stop--}}

{{--@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 会员分组列表</a></li>
            <a href="{{route('admin.users.group.create')}}" class="btn btn-w-m btn-info pull-right">添加分组</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>分组名称</th>
                            <th>分组说明</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groups as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->description}}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                       href="{{route('admin.users.group.edit',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    <a class="btn btn-xs btn-danger delete-group"
                                       data-href="{{route('admin.users.group.delete',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
    </div>
{{--@stop--}}

{{--@section('before-scripts-end')--}}
    <script>
        $('.delete-group').on('click', function () {
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
                            location = '{{route('admin.users.group.list')}}';
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

{{--@stop--}}
