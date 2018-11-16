{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            minuteStep : 1
        });

        $('#empty').click(function () {
            $('input[name=name]').val('');
            $('input[name=stime]').val('');
            $('input[name=mobile]').val('');
            $('input[name=etime]').val('');
        })

    </script>
{{--@stop--}}