
    <script>

        $('#base-form').ajaxForm({
            success: function (result) {
                if(result.status){
                    swal({
                        title: "编辑成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.shippingmethod.company')}}';
                    });
                }else{
                    swal('保存失败',result.message,'warning');
                }
            }
        });
    </script>