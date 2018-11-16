@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    批量订单发货
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/admin/css/plugins/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop



@section('body')
    <div class="row">
        <a href="/assets/template/order_send_template.xlsx" style="margin: 10px 0;display: block;"
           target="_blank">批量发货模板文件下载</a>

        <form method="POST" action="{{route('admin.orders.saveimport')}}" accept-charset="UTF-8"
              id="import_form" class="form-horizontal">
            <input type="hidden" class="_token" value="{{ csrf_token() }}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">批量订单发货：</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="upload_excel"/>
                        <div id="filePicker">选择文件</div>
                        <p class="update_true"></p>
                    </div>
                </div>


            </div>
        </form>
    </div>
@stop

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}

@stop
@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
    {{--<button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#delivers_from">保存--}}
    {{--</button>--}}
    <button type="button" id="send" class="ladda-button btn btn-primary disabled" data-style="slide-right"
            data-toggle="" data-target="#import_form">保存
    </button>

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
                /* $('#' + file.id).addClass('upload-state-done');*/
                /*$('#' + file.id).append('<input type="hidden" name="banner_pic" value="' + response.url + '" >');*/
                $('.update_true').html("已上传成功请点击保存批量修改订单");
                $("input[name='upload_excel']").val(response.file);

                $('#send').attr('type', 'submit');
                $('#send').attr('data-toggle', 'form-submit');
                $('#send').removeClass('disabled');

            });
        });

        function importorder() {
            $('#send').val('正在导入');
            $('#send').attr('data-toggle', '');
            $('#send').attr('disabled', true);
            return true;
        }

        $('#import_form').ajaxForm({

            beforeSubmit: importorder,

            success: function (result) {
                var data = result.data.error_list;
                var arr = '';

                if (result.status) {
                    if (data.length > 0) {
                        arr = '提示：' + data;
                    }

                    swal({
                        title: "操作成功！",
                        text: arr,
                        type: "success"
                    }, function () {
                        $('.addbut').val('导入成功');
                        $("#import_form").parents('.modal').modal('hide');
                        location.reload();
                    });
                }else{
                    swal({
                        title: "操作失败！",
                        text: '',
                        type: "error"
                    }, function () {
                        location.reload();
                    });
                }
            }
        });
    </script>
@stop






