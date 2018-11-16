
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
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop



@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.orders.savedeliver')}}" accept-charset="UTF-8"
              id="delivers_from" class="form-horizontal">
            <input type="hidden" name="order_id" value="{{ !empty($order_id) ?$order_id : '' }}">

            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">快递名称：</label>
                    <div class="col-sm-9">
                        {{--<input type="hidden" name="shipping_type" value="" id="shipping_type">--}}
                        @if($freightCompany->count()>0)
                            <select class="form-control" name="method_id" id="shopping">
                                <option value="" type="">请选择快递方式</option>
                                @foreach($freightCompany as $item)
                                <option value="{{$item->id}}" type="{{$item->code}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">快递单号：</label>
                    <div class="col-sm-9">
                        <input type="text" name="tracking" value=""
                               class="form-control" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">发货时间：</label>
                    <div class="col-sm-9">
                            <div class="date form_datetime">
                                <input type="text" class="form-control" name="delivery_time" value="{{date("Y-m-d H:i:s",time())}}"     placeholder="" readonly>
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
    <button type="submit"  id="send" class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit" data-target="#delivers_from">保存
    </button>

    <script>
        $('.form_datetime').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        $('.form_date').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_time').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });

        $('#shopping').change(function () {
           var val=$(this).val();
           var shipping_type=$('#shopping option').eq(val).attr('type');
           $('input[name=shipping_type]').val(shipping_type);

        })



        $(document).ready(function () {

            $('#delivers_from').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    shipping_id: {
                        validators: {
                            notEmpty: {
                                message: '请选择快递'
                            }
                        }
                    },
                    shipping_no: {
                        validators: {
                            notEmpty: {
                                message: '请输入快递单号'
                            }
                        }
                    },
                    shipping_type: {
                        validators: {
                            notEmpty: {
                                message: '请选择快递'
                            }
                        }
                    },
                    delivery_time: {
                        validators: {
                            notEmpty: {
                                message: '请选择发货时间'
                            }
                        }
                    }
                }
            }).on('success.form.fv', function (e) {

                $('#send').ladda().ladda('start');

                // Prevent form submission
                e.preventDefault();

                // Get the form instance
                var $form = $(e.target);

                // Get the FormValidation instance
                var bv = $form.data('formValidation');

                // Use Ajax to submit form data
                $.post($form.attr('action'), $form.serialize(), function(result) {

                    $("#delivers_from").parents('.modal').modal('hide');
                    location.reload();

                });

            });

        })
    </script>
@stop






