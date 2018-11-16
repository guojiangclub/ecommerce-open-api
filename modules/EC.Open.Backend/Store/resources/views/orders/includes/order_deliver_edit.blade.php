@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    {{ isset($deliver) ? '编辑' : '' }}订单发货
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop

@section('body')
    <div class="row">
        <form method="POST" action="{{route('admin.orders.savedeliver')}}" accept-charset="UTF-8" id="delivers_from"
              class="form-horizontal">
            <input type="hidden" name="shipping_id" value="{{ !empty($shipping->id) ?$shipping->id : '' }}">
            <input type="hidden" name="order_id" value="{{ !empty($order_id) ?$order_id : '' }}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">快递名称：</label>
                    <div class="col-sm-9">
                        @if($freightCompany->count()>0)
                            <select class="form-control" name="method_id" id="shopping">
                                <option value="" type="">请选择快递方式</option>
                                @foreach($freightCompany as $item)
                                    <option value="{{$item->id}}" type="{{$item->code}}"
                                            @if($item->id==$shipping->method_id) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">快递单号：</label>
                    <div class="col-sm-9">
                        <input type="text" name="tracking" value="{{$shipping->tracking}}" class="form-control"
                               placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">发货时间：</label>
                    <div class="col-sm-9">
                        <div class="date form_datetime">
                            <input type="text" class="form-control" name="delivery_time"
                                   value="{{$shipping->delivery_time}}" placeholder="" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
    {{--<button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#delivers_from">保存--}}
    {{--</button>--}}
    <button type="submit" id="send" class="ladda-button btn btn-primary" data-style="slide-right"
            data-toggle="form-submit" data-target="#delivers_from">保存
    </button>

    <script>
        $('.form_datetime').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        $('.form_date').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_time').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });

        $('#shopping').change(function () {
            var val = $(this).val();
            var shipping_type = $('#shopping option').eq(val).attr('type');
            $('input[name=shipping_type]').val(shipping_type);

        });

        $(document).ready(function () {
            $('#delivers_from').ajaxForm({
                success: function (result) {
                    if (!result.status) {
                        swal("", result.message, "error");
                    } else {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    }
                }
            });
        })
    </script>
@stop