{{--@section('after-scripts-end')--}}
    <script>
        function demo(){
            $('.addbut').val('正在导入');
            $('.addbut').attr('disabled',true);
            return true;
        }

        $('#base-form').ajaxForm({

            beforeSubmit:demo,

            success: function (result) {
                if(result.error_code==1){
                    swal("保存失败!", result.error, "error")
                }else{
                    var type="success";
                    if(result.data.num>0){
                        type="success"
                    }else{
                        type="error"
                    }


                    swal({
                        title: "导入成功"+result.data.num+"条",
                        text:result.data.sku,
                        type:type
                    }, function() {
                        $('.addbut').val('导入成功');
                        location = '{{route('admin.goods.index')}}';
                    });

                }



            }
        });
        $(function () {
            var uploader = WebUploader.create({

                // 选完文件后，是否自动上传。
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.excel',['_token'=>csrf_token()])}}',
                pick: '#filePicker',
                fileVal: 'upload_excel',
                accept: {
                    title: 'Excel',
                    extensions: 'xlsx,xlsm,xls'
//                    mimeTypes: 'image/*'
                }
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                /* $('#' + file.id).addClass('upload-state-done');*/
                /*$('#' + file.id).append('<input type="hidden" name="banner_pic" value="' + response.url + '" >');*/
                $('.update_true').html("已上传成功请点击保存导入商品库存");
                $("input[name='upload_excel']").val(response.file);
                $('.addbut').attr('type','submit');
                $('.addbut').removeClass('disabled');


            });

        })
    </script>

{{--@stop--}}