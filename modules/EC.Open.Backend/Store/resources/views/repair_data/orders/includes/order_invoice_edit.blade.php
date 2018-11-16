
@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    订单发票编辑
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop



@section('body')
    <div class="row">

            {!! Form::open( [ 'route' => ['admin.orders.invoice.update'], 'method' => 'POST', 'id' => 'invoice_from','class'=>'form-horizontal'] ) !!}

            <input type="hidden" name="id" value="{{ $invoice->id }}">

            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label"> 发票抬头：</label>
                    <div class="col-sm-9">
                        <input type="text" name="title" value="{{$invoice->title}}"   class="form-control" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label"> 发票类型：</label>
                    <div class="col-sm-9">
                        <select name="type" class="form-control">
                            @foreach($type as $item)
                                <option value="{{$item}}" {{$invoice->type == $item ? 'selected' : ''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label"> 发票内容：</label>
                    <div class="col-sm-9">
                        <select name="content" class="form-control">
                            @foreach($content as $item)
                                <option value="{{$item}}" {{$invoice->content == $item ? 'selected' : ''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label"> 发票金额：</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input class="form-control" type="text" name="amount" value="{{$invoice->amount}}">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label"> 发票编号：</label>
                    <div class="col-sm-9">
                        <input type="text" name="number" value="{{$invoice->number}}"
                               class="form-control" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label"> 开票时间：</label>
                    <div class="col-sm-9">
                            <div class="date form_datetime">
                                <input type="text" class="form-control" name="invoice_at"
                                       value="{{$invoice->invoice_at ? $invoice->invoice_at : date("Y-m-d H:i:s",time())}}"     placeholder="" readonly>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                    </div>
                </div>

            </div>
        {!! Form::close() !!}
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

    <button type="submit"  id="send" class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit" data-target="#invoice_from">保存
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



        $(document).ready(function () {

            $('#invoice_from').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    number: {
                        validators: {
                            notEmpty: {
                                message: '请输入发票编号'
                            }
                        }
                    },
                    invoice_at: {
                        validators: {
                            notEmpty: {
                                message: '请选择开票时间'
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

                    $("#invoice_from").parents('.modal').modal('hide');
                    location.reload();

                });

            });

        })
    </script>
@stop






