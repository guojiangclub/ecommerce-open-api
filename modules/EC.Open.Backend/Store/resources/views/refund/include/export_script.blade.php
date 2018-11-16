{{--@section('after-scripts-end')--}}
    <script type="text/javascript">
        $(function () {
            $.getScript('{{env("APP_URL").'/assets/wechat-backend/libs/datepicker/bootstrap-datetimepicker.js'}}',function () {
                $.fn.datetimepicker.dates['zh-CN'] = {
                    days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
                    daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
                    daysMin: ["日", "一", "二", "三", "四", "五", "六", "日"],
                    months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    today: "今天",
                    suffix: [],
                    meridiem: ["上午", "下午"]
                };

                $('.form_datetime').datetimepicker({
                    language: 'zh-CN',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    showMeridian: 1,
                    minuteStep: 1
                });

            });

        });


        $('.refund_status').change(function () {
            var sel_status = $(this).val();
            if (sel_status == 2) {
                $('#complete_time').show();
            } else {
                $('#complete_time').hide();
            }
        });


        $('#refunds_xls').on('click', function () {
            //var url = $(this).data('link');
            var url = '{{route('admin.refund.getExportData')}}';
            var type = $(this).data('type');

            var refund_status = $('.refund_status').val();
            var stime = $('input[name="stime"]').val();
            var etime = $('input[name="etime"]').val();
            var c_stime = '';
            var c_etime = '';

            if (refund_status == 2) {
                c_stime = $('input[name="c_stime"]').val();
                c_etime = $('input[name="c_etime"]').val();
            }


            url = url + '?type=' + type + '&refund_status=' + refund_status + '&stime=' + stime + '&etime=' + etime + '&c_stime=' + c_stime + '&c_etime=' + c_etime;

            $(this).data('link', url);
        });
    </script>
{{--@endsection--}}