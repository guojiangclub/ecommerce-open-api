@section('after-scripts-end')

    <script>
        $(function () {
            var uploader = WebUploader.create({

                // 选完文件后，是否自动上传。
                auto: true,
                swf: '{{url('env("APP_URL")./assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.uploadExcelFile',['_token'=>csrf_token()])}}',
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
              //  $("input[name='upload_excel']").val(response.file);

                $('.update_true').html("已上传成功请点击保存导入的数据");
                $('.add-button').removeAttr('disabled');
               // $('.myss').append('<input type="hidden" name="excel_file" value="' + response.file + '" >');
                $("input[name='excel_file']").val(response.file);

            });

        });


        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var path = $("input[name='excel_file']").val();

            var $this = $(this),

                    modalUrl = $(this).data('url') + '?path=' + path;

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(modalUrl, function () {

                });
            }
        });
    </script>

@stop