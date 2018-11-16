{{--@section('after-scripts-end')--}}
    <script>

        $('#base-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.users.group.list')}}';
                    });
                } else {
                    swal({
                        title: result.message,
                        text: "",
                        type: "warning"
                    });
                }
            }
        });
    </script>

{{--@stop--}}