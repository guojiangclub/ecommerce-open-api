{{--@extends('store-backend::dashboard')

@section ('title','编辑套餐')

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--    {!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop


@section('breadcrumbs')
    <h2>编辑套餐</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
        <li class="active">编辑套餐</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">

                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.suit.store')], 'method' => 'POST', 'id' => 'edit-suit-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*标题：</label>
                        <div class="col-sm-8">
                            <input type="hidden" class="form-control" name="type" value="1"/>
                            <input type="hidden" class="form-control" name="id" value="{{$suit->id}}"/>
                            <input type="text" class="form-control" name="title" placeholder=""
                                   value="{{$suit->title }}" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*生效时间：</label>
                        <div class="col-sm-3">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                                <input type="text" class="form-control inline" name="starts_at"
                                       value="{{$suit->starts_at}}"
                                       placeholder="点击选择开始时间" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
                                <input type="text" class="form-control" name="ends_at"
                                       value="{{$suit->ends_at}}" placeholder="点击选择结束时间" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('img', '*分享海报：', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <div class="pull-left" id="userAvatar">
                                <img src="{{$suit->img}}" style="
                                         margin-right: 23px;
                                         width: 200px;">
                                {!! Form::hidden('img', $suit->img, ['class' => 'form-control']) !!}
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
                                                                           value="1"
                                                                           @if($suit->status==1)
                                                                           checked
                                        @endif
                                >
                                有效</label>
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="0"

                                                                           @if($suit->status==0)
                                                                           checked
                                        @endif

                                > 无效</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否可获得积分：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="get_point" type="radio"
                                                                           value="1"
                                                                           @if($suit->get_point==1)
                                                                           checked
                                        @endif
                                >
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="get_point" type="radio"
                                                                           value="0"
                                                                           @if($suit->get_point==0)
                                                                           checked
                                        @endif
                                > 否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否可使用积分：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="use_point" type="radio"
                                                                           value="1"
                                                                           @if($suit->use_point==1)
                                                                           checked
                                        @endif
                                >
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="use_point" type="radio"
                                                                           value="0"

                                                                           @if($suit->use_point==0)
                                                                           checked
                                        @endif
                                > 否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*是否推荐：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="recommend" type="radio"
                                                                           value="1" {{$suit->recommend?'checked':''}}>
                                是</label>
                            <label class="checkbox-inline i-checks"><input name="recommend" type="radio"
                                                                           value="0" {{$suit->recommend?'':'checked'}}>
                                否</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*佣金比例： <i class="fa fa-question-circle"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        data-original-title="如何设置为0，则以分销商品设置为准"></i></label>
                        <div class="col-sm-8">
                            <div class="input-group m-b rate_div">
                                <input type="text" class="form-control" name="rate" placeholder=""
                                       value="{{$suit->rate}}" required="required"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">描述：</label>
                        <div class="col-sm-8">
                            <textarea name="describe" id="" cols="30" rows="6"
                                      class="col-sm-12">{{$suit->describe}}</textarea>
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
        $('#edit-suit-form').ajaxForm({
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











