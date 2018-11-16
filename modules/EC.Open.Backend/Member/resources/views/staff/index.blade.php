{{--@extends ('member-backend::layout')--}}

{{--@section ('title','用户管理')--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>员工管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.staff.index', '员工管理') !!}</li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}


{{--@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            @if(Session::has('message'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> 提示！</h4>
                    {{ Session::get('message') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.staff.create')}}" class="btn btn-w-m btn-info">添加员工</a>

                    {{--<a  id="chapter-create-btn" data-toggle="modal"--}}
                    {{--data-target="#modal" data-backdrop="static" data-keyboard="false"--}}
                    {{--data-url="{{route('admin.users.userexport')}}"--}}
                    {{--class="btn btn-primary pull-right  " style="margin-right: 5px">导出</a>--}}
                    <a id="chapter-create-btn" data-toggle="modal"
                       data-target="#modal" data-backdrop="static" data-keyboard="false"
                       data-url="{{route('admin.staff.staffimport')}}"
                       class="btn btn-primary" style="margin-right: 5px">导入员工数据</a>
                </div>
            </div>


                <form action="" method="get" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <select class="form-control" name="active_status">
                                <option value="" {{!empty(request('active_status')=='')?'selected ':''}} >在职状态</option>
                                <option value="1" {{!empty(request('active_status')==1)?'selected ':''}} >在职</option>
                                <option value="2" {{!empty(request('active_status')==2)?'selected ':''}} >离职</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="activate_at">
                                <option value="" {{!empty(request('activate_at')=='')?'selected ':''}} >激活状态</option>
                                <option value="activated" {{!empty(request('activate_at')=='activated')?'selected ':''}} >
                                    已激活
                                </option>
                                <option value="unactivated" {{!empty(request('activate_at')=='unactivated')?'selected ':''}} >
                                    未激活
                                </option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;激活时间</span>
                                    <input type="text" class="form-control inline" name="stime"
                                           value="{{request('stime')}}" placeholder="开始 " readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="etime" value="{{request('etime')}}"
                                           placeholder="截止" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="value" placeholder="员工号/姓名/邮箱/手机"
                                   value="{{!empty(request('value'))?request('value'):''}}"
                                   class=" form-control"> <span
                                    class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">查找</button> </span></div>
                    </div>
                        </div>
                </form>


            <div class="hr-line-dashed clearfix"></div>

            <div class="box box-primary">
                @include('member-backend::staff.includes.staff_list')
                <div class="pull-left">
                    {!! $staff->total() !!} 个员工
                </div>

                <div class="pull-right">
                    {!! $staff->render() !!}
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>


    </div>
    <div id="modal" class="modal inmodal fade"></div>
{{--@endsection--}}


{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script>
        $('.form_datetime').datetimepicker({
            minView: 0,
            format: "yyyy-mm-dd hh:ii",
            autoclose: 1,
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: true,
            minuteStep: 1,
            maxView: 4
        });
    </script>
{{--@stop--}}