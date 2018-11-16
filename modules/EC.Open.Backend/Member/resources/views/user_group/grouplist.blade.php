{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员等级管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员等级管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.grouplist', '会员等级管理') !!}</li>--}}
    {{--</ol>--}}

{{--@stop--}}

{{--@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 会员等级列表</a></li>
            <a href="{{route('admin.users.groupcreate')}}" class="btn btn-w-m btn-info pull-right">添加会员等级</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {{--@include('backend.auth.includes.header-buttons')--}}

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>等级名称</th>
                            <th>等级</th>
                            <th>消费下限</th>
                            <th>消费上限</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($group as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->grade}}</td>
                                <td>{{$item->min}}</td>
                                <td>{{$item->max}}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                       href="{{route('admin.users.groupcreate',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    @if($item->sort!=1)
                                    <a href="javascript:;"  class="btn btn-xs btn-danger delete-group"
                                       data-href="{{route('admin.users.deletedGroup',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>
                                        @endif
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
                        location = '{{route('admin.users.grouplist')}}';
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
