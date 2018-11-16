
@extends('member-backend::bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    导入员工信息
@stop
{!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}



@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.staff.saveimport')}}" accept-charset="UTF-8"
              id="import_form" class="form-horizontal">
            <input type="hidden" class="_token" value="{{ csrf_token() }}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">批量导入员工信息：</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="upload_excel"/>
                        <div id="filePicker">选择文件</div>
                        <p class="update_true"></p>
                        <a no-pjax href="{{url('/assets/template/staffImport.xlsx')}}"  target="_self">员工导入模板下载</a>
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
    {{--<button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#delivers_from">保存--}}
    {{--</button>--}}
    <button type="button"  id="send" class="ladda-button btn btn-primary disabled" data-style="slide-right" data-toggle="" data-target="#import_form">保存
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
                /* $('#' + file.id).addClass('upload-state-done');*/
                /*$('#' + file.id).append('<input type="hidden" name="banner_pic" value="' + response.url + '" >');*/
                $('.update_true').html("已上传成功请点击保存批量修改订单");
                $("input[name='upload_excel']").val(response.file);

                $('#send').attr('type','submit');
                $('#send').attr('data-toggle','form-submit');
                $('#send').removeClass('disabled');


            });


        })

        function importorder(){
            $('#send').val('正在导入');
            $('#send').attr('data-toggle','');
            $('#send').attr('disabled',true);
            return true;
        }

        $('#import_form').ajaxForm({

            beforeSubmit:importorder,

            success: function (result) {
//                if(data.length>0){
//                    arr='有如下订单未导入成功：'+data
//                }

//                if(result.error_code==1){
//                    swal("保存失败!", result.error, "error")
//                }else{
                var type="success";

                if(result.data.num>0){
                    type="success"
                }else{
                    type="error"
                }


                swal({
                    title: "导入成功"+result.data.num+"条",
                    text:result.data.abnormal,
                    type: type
                }, function() {
                    $('.addbut').val('导入成功');
                    $("#import_form").parents('.modal').modal('hide');
                    location.reload();
                });
                }

        });
    </script>
@stop






