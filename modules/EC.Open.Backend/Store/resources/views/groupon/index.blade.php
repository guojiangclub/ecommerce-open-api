{{--@extends('store-backend::dashboard')

@section ('title','拼团活动列表')

@section('breadcrumbs')

    <h2>拼团活动列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">拼团活动列表</li>
    </ol>

@endsection

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop


@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status','') }}">
                <a no-pjax href="{{route('admin.promotion.groupon.index')}}">所有活动</a>
            </li>

            <li class="{{ Active::query('status','future') }}">
                <a no-pjax href="{{route('admin.promotion.groupon.index',['status'=>'future'])}}">未开始</a>
            </li>

            <li class="{{ Active::query('status','on') }}">
                <a no-pjax href="{{route('admin.promotion.groupon.index',['status'=>'on'])}}">进行中</a>
            </li>

            <li class="{{ Active::query('status','end') }}">
                <a no-pjax href="{{route('admin.promotion.groupon.index',['status'=>'end'])}}">已结束</a>
            </li>

        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                <div class="panel-body">
                    {!! Form::open( [ 'route' => ['admin.promotion.groupon.index'], 'method' => 'get', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
                    <input type="hidden" name="status" value="{{request('status')}}">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="title" placeholder="输入拼团活动标题搜索">
                        </div>

                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary">搜索</button>

                            <a no-pjax href="{{ route('admin.promotion.groupon.create')}}"
                               class="btn btn-primary">新建拼团活动</a>
                        </div>

                        <div class="col-sm-6">

                                <label class="col-sm-5 control-label">拼团活动列表页前端链接：</label>
                                <div class="col-sm-7" style="position: relative">
                                    <input type="text"
                                           value="{{settings('mobile_domain_url')}}/#!/store/groupon">
                                    <label class="label label-danger copyBtn">复制链接</label>
                                </div>

                        </div>

                    </div>
                    {!! Form::close() !!}

                    <div class="table-responsive">
                        @include('store-backend::groupon.includes.list')

                    </div><!-- /.box-body -->

                </div>
            </div>
        </div>
    </div>
{{--@endsection



@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script>
        $('.close-groupon').on('click',function () {
            var postUrl=$(this).data('url');

            swal({
                title: "您真的要删除该活动吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, function () {
                $.post(postUrl,{_token:_token},function (result) {
                    if (!result.status) {
                        swal("删除失败!", result.message, "error")
                    } else {
                        swal({
                            title: "删除成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location = '{{route('admin.promotion.groupon.index')}}';
                        });
                    }
                })
            });



        });
    </script>
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    <script>
        $('.copyBtn').zclip({
            path: "{{url('assets/backend/libs/jquery.zclip/ZeroClipboard.swf')}}",
            copy: function () {
                return $(this).prev().val();
            }
        });
    </script>
{{--@stop--}}



