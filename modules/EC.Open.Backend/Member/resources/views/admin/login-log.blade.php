{{--@extends ('member-backend::layout')--}}

{{--@section ('title','管理员管理')--}}


{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/simditor/styles/simditor.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/dataTables/datatables.min.css') !!}

{{--@stop--}}


{{--@section ('breadcrumbs')--}}
    {{--<h2>管理员登录日志</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">管理员登录日志</li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('content')--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content" style="display: block;">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example dataTable"
                               id="logs-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>用户ID</th>
                                <th>IP地址</th>
                                <th>IP详情</th>
                                <th>设备</th>
                                <th>系统</th>
                                <th>浏览器</th>
                                <th>登录日期</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--@stop--}}


{{--@section('after-scripts-end')--}}

    {!! Html::script(env("APP_URL").'/assets/backend/libs/dataTables/datatables.min.js') !!}

    <script type="text/javascript">
        $(function () {
            $('#logs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.manager.log.datatable') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'admin_id', name: 'user_id'},
                    {data: 'ip', name: 'ip'},
                    {data: 'ip_info', name: 'ip_info'},
                    {data: 'device', name: 'device'},
                    {data: 'platform', name: 'platform'},
                    {data: 'browser', name: 'browser'},
                    {data: 'created_at', name: 'created_at'}
                ]
            });
        });
    </script>

{{--@endsection--}}