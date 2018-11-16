{{--@section('after-scripts-end')--}}
    <script>
        $('.form_datetime').datetimepicker({
            minView: "month",
            format: "yyyy-mm-dd",

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
        $(document).ready(function () {

            $('#create-user-form').ajaxForm({
                success: function (result) {
//                console.log(result);
                    if(result.code==400)
                    {
                        swal("", result.message, "error");
                    }else{
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function() {
                            window.location = '{{route('admin.staff.index')}}';
                        });
                    }

                }
            });
            {{--$('#create-user-form').formValidation({--}}
                {{--framework: 'bootstrap',--}}
                {{--icon: {--}}
                    {{--valid: 'glyphicon glyphicon-ok',--}}
                    {{--invalid: 'glyphicon glyphicon-remove',--}}
                    {{--validating: 'glyphicon glyphicon-refresh'--}}
                {{--},--}}
                {{--fields: {--}}
                    {{--staff_id: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: '请输入员工号'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},name: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: '请输入用户名'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}, mobile: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: '请输入邮箱或手机号码'--}}
                            {{--},--}}
                            {{--regexp: {--}}
                                {{--regexp: /^1[3|4|5|7|8][0-9]{9}$/,--}}
                                {{--message: '手机号码格式错误'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--email: {--}}
                        {{--enabled: false,--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: '请输入邮箱或手机号码'--}}
                            {{--},--}}
                            {{--regexp: {--}}
                                {{--regexp:/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/,--}}
                                {{--message: '邮箱格式不错误'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}

                {{--}--}}
            {{--}).on('keyup', '[name="mobile"], [name="email"]', function (e) {--}}
                {{--var email = $('#create-user-form').find('[name="email"]').val(),--}}
                        {{--mobile = $('#create-user-form').find('[name="mobile"]').val(),--}}

                        {{--fv = $('#create-user-form').data('formValidation');--}}

                {{--switch ($(this).attr('name')) {--}}
                        {{--// User is focusing the mobile field--}}
                    {{--case 'mobile':--}}
                        {{--fv.enableFieldValidators('email', mobile === '').revalidateField('email');--}}

                        {{--if (mobile && fv.getOptions('mobile', null, 'enabled') === false) {--}}
                            {{--fv.enableFieldValidators('mobile', true).revalidateField('mobile');--}}
                        {{--} else if (mobile === '' && email !== '') {--}}
                            {{--fv.enableFieldValidators('mobile', false).revalidateField('mobile');--}}
                        {{--}--}}
                        {{--break;--}}

                        {{--// User is focusing the drivers license field--}}
                    {{--case 'email':--}}
                        {{--if (email === '') {--}}
                            {{--fv.enableFieldValidators('mobile', true).revalidateField('mobile');--}}
                        {{--} else if (mobile === '') {--}}
                            {{--fv.enableFieldValidators('mobile', false).revalidateField('mobile');--}}
                        {{--}--}}

                        {{--if (email && mobile === '' && fv.getOptions('email', null, 'enabled') === false) {--}}
                            {{--fv.enableFieldValidators('email', true).revalidateField('email');--}}
                        {{--}--}}
                        {{--break;--}}

                    {{--default:--}}
                        {{--break;--}}
                {{--}--}}
            {{--}).find('input[name="assignees_roles[]"]')--}}
                    {{--// Init icheck elements--}}
                    {{--/*.iCheck({--}}
                     {{--// The tap option is only available in v2.0--}}
                     {{--increaseArea: '20%',--}}
                     {{--checkboxClass: 'icheckbox_square-green',--}}
                     {{--radioClass: 'iradio_square-green'--}}
                     {{--})*/--}}
                    {{--// Called when the radios/checkboxes are changed--}}
                    {{--.on('ifChanged', function (e) {--}}
                        {{--// Get the field name--}}
                        {{--var field = $(this).attr('name');--}}
                        {{--$('#create-user-form').formValidation('revalidateField', field);--}}
                    {{--})--}}
                    {{--.end();--}}
        });

    </script>

{{--@stop--}}