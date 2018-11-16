@extends('admin::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    批量导入会员积分
@stop


<style type="text/css">
    .upload-btn-box {
        border: 3px dashed #e6e6e6;
        height: 100px;
        text-align: center;

    }

    .upload-btn-box #filePicker {
        height: 34px;
        margin-top: 40px
    }

    .update_true {
        text-align: center
    }
</style>
{!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}

@section('body')
    <div class="row">
        <div class="upload-btn-box">
            <p style="text-align: left">
                <a href="/assets/template/user_point_import_template.xlsx"
                   target="_blank">模板文件下载</a>
            </p>

            <input type="hidden" name="upload_excel"/>
            <div id="filePicker">点击选择文件</div>
            <p class="update_true"></p>
        </div>


        <div class="progress progress-striped active" id="progress-box" style="display: none;">
            <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar"
                 class="progress-bar progress-bar-danger">
                <span class="sr-only">40% Complete (success)</span>
            </div>
        </div>
        <div id="down"></div>
    </div>

@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}

@section('footer')
    <button class="btn btn-primary" type="button" id="submit_btn" disabled>导入</button>
    <button type="button" class="btn btn-link" data-dismiss="modal" id="close-modal">关闭</button>

    <script>
        var calculateUrl;
        $(function () {
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                swf: '{{url('libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.excel',['_token'=>csrf_token()])}}',
                pick: '#filePicker',
                fileVal: 'upload_excel',
                accept: {
                    title: 'Excel',
                    extensions: 'xlsx,xlsm,xls'
                }
            });

            uploader.on('uploadSuccess', function (file, response) {
                $('.update_true').html("文件上传成功,请点击导入按钮进行导入");
                $("input[name='upload_excel']").val(response.file);
                $('#submit_btn').removeAttr('disabled');
                calculateUrl = '{{route('admin.member.points.getImportDataCount')}}' + '?path=' + response.file;
            });
        });


        $('#submit_btn').on('click', function () {
            $('#progress-box').show();
            $(this).attr('disabled', true);
            $('.update_true').html("数据导入中...");
            $.get(calculateUrl, function (result) {
                if (result.data.status == 'goon') {
                    var importUrl = result.data.url;
                    _get(importUrl);
                }
            });
        });


        function _get(url) {
            $.get(url, function (result) {
                if (result.data.status == 'goon') {
                    var current = result.data.current_page;
                    var total = result.data.total;
                    var process = (current / total).toFixed(2);
                    $('.progress-bar').css('width', (process * 100 - 2) + '%');
                    _get(result.data.url);

                } else {
                    $('.progress-bar').css('width', '98%');
                    setTimeout(function () {
                        swal({
                            title: "导入成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            window.location.reload();
                        });
                    }, 200);
                }
            });
        }

    </script>
@stop






