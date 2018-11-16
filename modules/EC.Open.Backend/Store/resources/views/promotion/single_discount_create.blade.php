{{--@extends('store-backend::dashboard')


@section('after-styles-end')--}}
    {!! Html::style('assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style('assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style('assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}

    {!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
    <style type="text/css">
        .money {
            width: 200px;
            display: inline-block
        }

        .pd20 {
            padding-top: 20px
        }

        .upload-btn-box {
            border: 3px dashed #e6e6e6;
            height: 150px;
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

{{--@stop


@section('breadcrumbs')
    <h2>新建单品折扣</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">新建单品折扣</li>
    </ol>
@endsection

@section('content')--}}

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">

                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.singleDiscount.store')], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">折扣名称：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="base[title]" placeholder="" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">折扣说明：</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="base[description]"></textarea>
                        </div>
                    </div>

                    <div class="form-group" id="two-inputs">
                        <label class="col-sm-2 control-label">生效时间：</label>
                        <div class="col-sm-4">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                                <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200" size="20" value=""
                                       placeholder="点击选择时间" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            <div id="date-range12-container"></div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
                                <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201" size="20" value=""
                                       placeholder="" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">状态：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                                           value="1" checked>
                                有效</label>
                            <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                                           value="0"> 无效</label>
                        </div>
                    </div>

                    <div class="form-group" style="display: none">
                        <label class="col-sm-2 control-label">前台是否显示倒计时：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="base[show_countdown]" type="radio"
                                                                           value="1" checked>
                                显示</label>
                            <label class="checkbox-inline i-checks"><input name="base[show_countdown]" type="radio" checked
                                                                           value="0"> 不显示</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">新增折扣：</label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-primary add-data" data-type="single">新增单品折扣</button>
                            <button type="button" class="btn btn-primary add-data" data-type="batch">批量上传单品折扣</button>
                            <input type="hidden" name="data_type" value="single">
                        </div>
                    </div>


                    <div class="form-group hidden" id="single-list-box">
                        <div class="col-sm-10 col-sm-offset-2">
                            <table class="table table-bordered" id="discount-table">
                                <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>折扣设置</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button class="btn btn-primary" id="add-condition" type="button">添加单品
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>

                    <div class="form-group hidden" id="upload-box">
                        <div class="col-sm-10 col-sm-offset-2">
                            <div class="upload-btn-box">
                                <p style="text-align: left">
                                    <a href="/assets/template/discount_import_template.xlsx"
                                       target="_blank">模板文件下载</a>
                                </p>

                                <input type="hidden" name="upload_excel"/>
                                <div id="filePicker">点击选择文件</div>
                                <p class="update_true"></p>
                            </div>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit" id="submit_btn">保存设置</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>

    <script type="text/x-template" id="discount_template">
        <tr class="showData">
            <td><input type="text" name="_conditionValue[]" class="form-control" value=""></td>
            <td>
                <div class="i-checks"><label> <input type="radio" name="type[{NUM}][]" class="type"> <i></i> 销售价 <input
                                type="text" name="type_cash[]" class="form-control money" value=""> 元 </label></div>
                <div class="i-checks"><label> <input type="radio" name="type[{NUM}][]" class="type"> <i></i> 打 <input
                                type="text" name="type_discount[]" class="form-control money" placeholder="如打6折填入数字6即可">
                        折 </label></div>
            </td>
            <td>
                <a href="javascript:;" onclick="deltr(this)">删除</a>
            </td>
        </tr>
    </script>

    <div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
{{--@stop

@section('after-scripts-end')
    {!! Html::script('assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}--}}
    @include('store-backend::promotion.includes.singleScript')
    <script>
        $(function () {


            $('#create-discount-form').ajaxForm({

                beforeSubmit: function () {
                    var that = $('#submit_btn');
                    that.text('数据处理中');
                    that.attr('disabled', 'true');
                },
                success: function (result) {

                    if (result.status && !result.data.status) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location = '{{route('admin.promotion.singleDiscount.index')}}';
                        });
                    }

                    if (result.status && result.data.status == 'goon') {
                        var data = result.data;
                        $('#modal').modal('show');
                        $('#modal').html('').load(data.url, function () {

                        });
                    }


                    if (!result.status) {
                        swal({
                            title: "保存失败",
                            text: result.message,
                            type: "error"
                        }, function () {
                            var that = $('#submit_btn');
                            that.text('保存设置');
                            that.removeAttr('disabled');
                        });
                    }

                }
            });
        })
    </script>
{{--@stop--}}
