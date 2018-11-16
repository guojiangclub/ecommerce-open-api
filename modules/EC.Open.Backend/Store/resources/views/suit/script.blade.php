<script>
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


    var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
    // 初始化Web Uploader
    var uploader = WebUploader.create({
        auto: true,
        swf: '{{url('assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
        server: '{{route('upload.image',['_token'=>csrf_token()])}}',
        pick: '#filePicker',
        fileVal: 'upload_image',
        accept: {
            title: 'Images',
            extensions: 'jpg,jpeg,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        }
    });
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, response) {
        $('#userAvatar img').attr("src", response.url);
        $('#userAvatar input').val(response.url);
    });
</script>