<script>

    $('#base-form').ajaxForm({
        success: function (result) {
            console.log(result);
            if (result.error_code == 1) {
                swal("保存失败!", result.error, "error")
            } else {
                swal({
                    title: "添加成功！",
                    text: "",
                    type: "success"
                }, function () {
                    //$('.addbut').val('导入成功');
                    location = '/admin/store/registrations/';
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
            $('.update_true').html("已上传成功请点击执行按钮导入授权码");
            $("input[name='upload_excel']").val(response.file);
            $('.add-button').removeAttr('disabled');
        });
    });


    /*批量导入modal*/
    $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
        var path = $("input[name='upload_excel']").val();
        var import_type = $("select[name='import_type']").val();

        var $this = $(this),
                href = $this.attr('href'),
                modalUrl = $(this).data('url') + '?path=' + path + '&import_type=' + import_type;

        if (modalUrl) {
            var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
            $target.modal('show');
            $target.html('').load(modalUrl, function () {

            });
        }
    });
</script>
