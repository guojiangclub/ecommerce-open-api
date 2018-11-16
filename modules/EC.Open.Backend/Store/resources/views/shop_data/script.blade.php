{{--@section('after-scripts-end')--}}
    <script type="text/javascript">
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
            minuteStep: 5,
            maxView:1,
            minView:'month'

        });

        $('#shop_data_xls').on('click', function () {
            var url = '{{route('admin.store.getExportData')}}';
            var type = $(this).data('type');

            var stime = $('input[name="stime"]').val();
            var etime = $('input[name="etime"]').val();

            if(!stime || !etime){
                swal('请选择时间','','warning');
                return false;
            }

            var st_arr=stime.split('-');
            var et_arr=etime.split('-');

            if(st_arr[1] !== et_arr[1]){
                swal('请选择同月份时间范围','','warning');
                return false;
            }

            if(st_arr[2] > et_arr[2]){
                swal('日期选择错误','','warning');
                return false;
            }

            url = url + '?type=' + type + '&stime=' + stime + '&etime=' + etime;

            $(this).data('link', url);
        });

    </script>
{{--@endsection--}}