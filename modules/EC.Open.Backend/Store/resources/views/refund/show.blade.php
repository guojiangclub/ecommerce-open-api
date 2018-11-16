{{--@extends ('store-backend::dashboard')

@section ('title','售后详情')

@section('after-styles-end')--}}
{!! Html::style(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda-themeless.min.css') !!}
{{--@stop

@section ('breadcrumbs')

    <h2>售后详情</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('admin.refund.index', '售后申请列表') !!}</li>
        <li class="active">编辑</li>
    </ol>

@stop

@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a aria-expanded="true" data-toggle="tab" href="#tab-1">申请详情</a></li>
            <li class=""><a aria-expanded="false" data-toggle="tab" href="#tab-2"> 操作日志</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    @include('store-backend::refund.include.refund_detail')
                </div>
            </div>

            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    @include('store-backend::refund.include.refund_log')
                </div>
            </div>

        </div>

    </div>


{{--@endsection

@section('before-scripts-end')--}}
{{--    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
    <script>
        $("#channel").change(function () {
            var val = $(this).children('option:selected').val();
            if (val == "artificial") {
                $('#channel_tips').show().html('请确认财务人员已将款项退给用户');
            } else if (val == "wechat") {
                $('#channel_tips').show().html('系统将自动打款至用户的微信钱包账户,请谨慎操作');
            } else {
                $('#channel_tips').hide().html('');
            }
        });

        $('#base-form').ajaxForm({
            beforeSubmit:function () {
                $('.btn-primary').ladda().ladda('start');
//                $('button[type="button"],button[type="submit"]').on('click', function () {
//                    console.log('aa');
//                    $(this).attr('disabled',"true");
//                });
            },
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "操作成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                } else {
                    swal('操作失败', result.message, 'error');
                }

            }
        });

        $(function () {


            $('#reject').on('click', function () {
                var data = {
                    id: $('input[name="id"]').val(),
                    remarks: $('#remarks').val(),
                    action: 'reject',
                    log_action: 'reject',
                    status: $('input[name="status"]').val(),
                    _token: _token
                };
                var rejectUrl = '{{route('admin.refund.store')}}';
                $.post(rejectUrl, data, function (result) {
                    if (result.status) {
                        swal({
                            title: "操作成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal('操作失败', result.message, 'error');
                    }
                });
            });
        });
    </script>

{{--@stop--}}
