{{--@extends('store-backend::dashboard')

@section ('title','创建套餐')

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
    {{--{!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop


@section('breadcrumbs')
    <h2>创建套餐</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
        <li class="active">创建套餐</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">

                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.suit.store')], 'method' => 'POST', 'id' => 'create-suit-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*标题：</label>
                        <div class="col-sm-8">
                            <input type="hidden" class="form-control" name="type" value="1"/>
                            <input type="text" class="form-control" name="title" placeholder="" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*生效时间：</label>
                        <div class="col-sm-3">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                                <input type="text" class="form-control inline" name="starts_at" value="{{date("Y-m-d H:i:s",time())}}"
                                       placeholder="点击选择开始时间" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
                                <input type="text" class="form-control" name="ends_at"
                                       value="{{date("Y-m-d H:i:s",time()+60*60*24*30)}}" placeholder="点击选择结束时间" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('img', '*分享海报：', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <div class="pull-left" id="userAvatar">
                                <img src="" style="
                                         margin-right: 23px;
                                         width: 200px;">
                                {!! Form::hidden('img', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="clearfix" style="padding-top: 22px;">
                                <div id="filePicker">添加图片</div>
                                <p style="color: #b6b3b3">温馨提示：请上传宽度为750，格式为JPG的图片</p>
                            </div>
                        </div>
                    </div><!--form control-->

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*状态：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="1" >
                                有效</label>
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="0" checked> 无效</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否可获得积分：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="get_point" type="radio"
                                                                           value="1" checked>
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="get_point" type="radio"
                                                                           value="0"> 否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否可使用积分：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="use_point" type="radio"
                                                                           value="1" checked>
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="use_point" type="radio"
                                                                           value="0"> 否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否推荐：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="recommend" type="radio"
                                                                           value="1">
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="recommend" type="radio"
                                                                           value="0" checked> 否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*佣金比例： <i class="fa fa-question-circle"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        data-original-title="如何设置为0，则以分销商品设置为准"></i></label>
                        <div class="col-sm-8">
                            <div class="input-group m-b rate_div">
                                <input type="text" class="form-control" name="rate" placeholder="" value="0"
                                       required="required"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                            <label class="col-sm-2 control-label">描述：</label>
                            <div class="col-sm-8">
                            <textarea name="describe" id="" cols="30" rows="6" class="col-sm-12"></textarea>
                            </div>
                     </div>


                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div id="spu_modal" class="modal inmodal fade"></div>

            </div>
        </div>


{{--@endsection

@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
{{--    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script('assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::suit.script')
    <script>
        $('input[name=rate]').change(function () {
            var num = $('input[name=rate]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if (!reg.test(num)) {
                $('input[name=rate]').val(0);
            }
        });

        $('#create-suit-form').ajaxForm({
            beforeSubmit: function (res) {
                var rate = $('input[name=rate]').val();
                if (!(/(^[1-9]\d*$)/.test(rate)) && rate != 0) {
                    swal("保存失败!", "佣金比例必须为正整数或0", "warning");
                    return false;
                }

            },
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.promotion.suit.index')}}';
                    });
                }

            }
        });
    </script>
{{--@stop--}}











