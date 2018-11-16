{{--@extends ('member-backend::layout')--}}

{{--@section ('title','实体卡申请管理')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>实体卡申请管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.balances.list', '实体卡申请管理') !!}</li>--}}
    {{--</ol>--}}

{{--@stop--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}


{{--@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            <form action="" method="get">
                <div class="col-md-6">
                    <div class="col-sm-6">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;时间</span>
                            <input type="text" class="form-control inline" name="stime" id="stime"
                                   value="{{request('stime')?request('stime'):date("Y-m-d",time())}}" placeholder="开始 "
                                   readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" name="etime" id="etime"
                                   value="{{request('etime')?request('etime'):date("Y-m-d",time())}}" placeholder="截止"
                                   readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">查找</button>

                    <a data-toggle="modal-filter" style="margin-left: 20px"
                       data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                       data-link="{{route('admin.card.getExportData',['type'=>'xls'])}}" id="all-xls"
                       data-url="{{route('admin.export.index',['toggle'=>'all-xls'])}}"
                       data-type="xls" class="btn btn-primary margin-bottom"
                       href="javascript:;">导出excel</a>

                    <button type="button" id="download_avatar" class="btn btn-primary" style="margin-left: 20px">下载头像
                    </button>
                </div>
            </form>

            <div class="box box-primary">

                <div class="">
                    <table class="table" data-page-size="15">
                        <thead>
                        <!--tr-th start-->
                        <tr>
                            <th>姓名</th>
                            <th>拼音</th>
                            <th>出生日期</th>
                            <th>所在地</th>
                            <th>手机</th>
                            <th>邮箱</th>
                            <th>申请时间</th>
                            <th>申请用户</th>
                        </tr>
                        <!--tr-th end-->

                        </thead>

                        <tbody>
                        @foreach ($cards as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->e_name}}</td>
                                <td>{{$item->birthday}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->mobile}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    {{\iBrand\EC\Open\Member\Backend\Models\User::find($item->user_id)?\iBrand\EC\Open\Member\Backend\Models\User::find($item->user_id)->mobile:''}}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="pull-right">
                    {!! $cards->appends(['stime'=>request('stime'),'etime'=>request('etime')])->render() !!}
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>


    </div>

    <div id="download_modal" class="modal inmodal fade"></div>

{{--@stop--}}

{{--@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
    <script>
        $('.form_datetime').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            minuteStep: 1,
            minView: 4
        });

        /**
         * 导出搜索结果
         */
        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var $this = $(this),
                    href = $this.attr('href'),
                    modalUrl = $(this).data('url');

            var param = funcUrlDel('page');

            var url = '{{route('admin.users.entity.getExportData')}}';
            var type = $(this).data('type');

            url = url + '?type=' + type + '&' + param;

            $(this).data('link', url);

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(modalUrl, function () {

                });
            }
        });

        $('#download_avatar').on('click', function () {
            var stime = $('#stime').val();
            var etime = $('#etime').val();
            var url = '{{route('admin.users.entity.zipFiles')}}';
            window.open(url + '?stime=' + stime + '&etime=' + etime);
        });
    </script>
{{--@endsection--}}