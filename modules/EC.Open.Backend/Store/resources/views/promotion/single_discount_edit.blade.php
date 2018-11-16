{{--@extends('store-backend::dashboard')


@section('after-styles-end')--}}
{!! Html::style('assets/backend/libs/loader/jquery.loader.min.css') !!}
{!! Html::style('assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
{!! Html::style('assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
<style type="text/css">
    .money {
        width: 100px;
        display: inline-block
    }

    .pd20 {
        padding-top: 20px
    }
</style>

{{--@stop


@section('breadcrumbs')
    <h2>编辑单品折扣</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">编辑单品折扣</li>
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
                        <input type="text" class="form-control" name="base[title]" value="{{$discount->title}}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">折扣说明：</label>
                    <div class="col-sm-10">
                            <textarea class="form-control"
                                      name="base[description]">{{$discount->description}}</textarea>
                    </div>
                </div>

                <div class="form-group" id="two-inputs">
                    <label class="col-sm-2 control-label">生效时间：</label>
                    <div class="col-sm-3">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                            <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200"
                                   size="20" value="{{$discount->starts_at}}"
                                   placeholder="点击选择时间" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                        <div id="date-range12-container"></div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
                            <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201"
                                   size="20" value="{{$discount->ends_at}}"
                                   placeholder="" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">状态：</label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                                       value="1"
                                                                       @if($discount->status_flag == 1) checked @endif >
                            有效</label>
                        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                                       @if($discount->status_flag == 0) checked
                                                                       @endif
                                                                       value="0"> 无效</label>
                    </div>
                </div>

                <div class="form-group" style="display: none">
                    <label class="col-sm-2 control-label">前台是否显示倒计时：</label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline i-checks"><input name="base[show_countdown]" type="radio"
                                                                       value="1"
                                                                       @if($discount->show_countdown == 1) checked @endif>
                            显示</label>
                        <label class="checkbox-inline i-checks"><input name="base[show_countdown]" type="radio"
                                                                       value="0"
                                                                       @if($discount->show_countdown == 0) checked @endif>
                            不显示</label>
                    </div>
                </div>

                @include('store-backend::promotion.includes.single_template')

                <script type="text/x-template" id="discount_template">
                    <tr class="showData">
                        <td><input type="text" name="_conditionValue[]" class="form-control" value=""></td>
                        <td>
                            <div class="i-checks"><label> <input type="radio" name="type[{NUM}][]" class="type">
                                    <i></i> 销售价 <input type="text" name="type_cash[]" class="form-control money"
                                                       value=""> 元 </label></div>
                            <div class="i-checks"><label> <input type="radio" name="type[{NUM}][]" class="type">
                                    <i></i> 打 <input type="text" name="type_discount[]" class="form-control money"
                                                     value=""> 折 </label></div>
                        </td>
                        <td>
                            <a href="javascript:;" onclick="deltr(this)">删除</a>
                        </td>
                    </tr>
                </script>


                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <input type="hidden" name="delID" id="delID" value="">
                        <input type="hidden" name="id" value="{{$discount->id}}">
                        <input type="hidden" name="data_type" value="{{$discount->path?'batch':'single'}}">
                        <input type="hidden" name="upload_excel" value="{{$discount->path}}">
                        <button class="btn btn-primary" type="submit">保存设置</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
{{--@stop--}}

{{--@section('after-scripts-end')
    {!! Html::script('assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}--}}
@include('store-backend::promotion.includes.singleScript')
<script>
    $(function () {
        $('#create-discount-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.promotion.singleDiscount.index')}}';
                    });
                } else {
                    swal("保存失败!", result.message, "error")
                }

            }
        });
    })
</script>

{!! Html::script('assets/backend/libs/jquery.el/common.js') !!}
{!! Html::script('assets/backend/libs/jquery.el/jquery.http.js') !!}
{!! Html::script('assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
<script>

    function getConditionData() {
        $.ajax({
            type: 'GET',
            url: "{{route('admin.promotion.singleDiscount.getDataList',['id' => $discount->id])}}",
            data: {},
            success: function (result) {
                $('.page-discount-list').append(result);
                $('.page-discount-list').find("input[type='radio']").iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });

                radioEvents();
                conditionPages();
            }
        });
    }


    @if(!$discount->path)
            $(document).ready(function () {
        getConditionData();
    });
    @endif

</script>
{{--@stop--}}
