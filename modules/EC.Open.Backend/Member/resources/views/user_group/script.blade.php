{{--@section('after-scripts-end')--}}
    <script>

        $('#base-form').ajaxForm({
            success: function (result) {
                if(result.error_code==1){
                    swal({
                        title: result.error,
                        text: "",
                        type: "warning"
                    });
                }else{
                    swal({
                        title: "编辑成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.users.grouplist')}}';
                    });
                }



            }
        });
    </script>

{{--@stop--}}