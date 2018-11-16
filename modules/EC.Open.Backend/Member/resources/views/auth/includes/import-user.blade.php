@extends('member-backend::bootstrap_modal')

@section('modal_class')
    modal-lg
@stop

@section('title')
    批量导入会员
@stop

{!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}


@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.users.importUser.saveImport')}}" accept-charset="UTF-8"
              id="import_form" class="form-horizontal">
            <input type="hidden" class="_token" value="{{ csrf_token() }}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-4 control-label">批量导入会员：</label>
                    <div class="col-sm-8">
                        <input type="hidden" name="upload_excel"/>
                        <div id="filePicker">选择文件</div>
                        <p class="update_true"></p>
                        <a no-pjax  href="{{url('/assets/template/member_import.xlsx')}}" target="_self">会员导入模板下载</a>
                    </div>
                </div>


            </div>
        </form>
    </div>
@stop

{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" id="send" class="ladda-button btn btn-primary disabled" data-style="slide-right"
            data-toggle="" data-target="#import_form">保存
    </button>
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    <script>
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

                $('.update_true').html("文件上传成功，请点击保存按钮");
                $("input[name='upload_excel']").val(response.file);

                $('#send').attr('type', 'submit');
                $('#send').attr('data-toggle', 'form-submit');
                $('#send').removeClass('disabled');

            });

        });

        function importorder() {
            $('#send').text('正在导入');
            $('#send').attr('data-toggle', '');
            $('#send').attr('disabled', true);
            return true;
        }

        $('#import_form').ajaxForm({

            beforeSubmit: importorder,

            success: function (result) {
                swal({
                    title: "导入成功",
                    text: result.data.abnormal,
                    type: "success"
                }, function () {
                    location.reload();
                });
            }

        });
    </script>
@stop






