@section('after-scripts-end')
    <script>
        $('#base-form').ajaxForm({
            success: function (result) {
                if(result.status){
                    $("input[name='id']").val(result.data);
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '/admin/cms/aditem?ad_id='+result['ad_id'];
                    });
                }else{
                    swal({
                        title: "保存失败",
                        text: result.error,
                        type: "error"
                    }, function() {

                    });
                }

            }
        });


        $('#base-child-form').ajaxForm({
            success: function (result) {

                if(result.status) {
                    $("input[name='id']").val(result.data);
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '/admin/cms/aditem/' + result['pid'] + '/edit?ad_id=' + result['ad_id'];
                    });
                }else{
                    swal({
                        title: "保存失败",
                        text: result.error,
                        type: "error"
                    }, function() {

                    });
                }


            }
        });

        $(function () {
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.image',['_token'=>csrf_token()])}}',
                pick: '#filePicker',
                fileVal: 'upload_image',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                $('.banner-image').attr('src', response.url).show();
                $("input[name='image']").val(response.url)
            });
        })
    </script>
@stop