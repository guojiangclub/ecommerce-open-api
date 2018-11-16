@section('after-scripts-end')
    <script>
        $('#base-form').ajaxForm({
            success: function (result) {
//                console.log(result)
                $("input[name='id']").val(result.data);
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function() {
                    location = '{{route('ad.index')}}';
                });
            }
        });




    </script>
@stop