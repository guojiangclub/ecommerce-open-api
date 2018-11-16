
@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    商品生产状态编辑
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop



@section('body')
    <div class="row">

            {!! Form::open( [ 'route' => ['admin.orders.produce.update'], 'method' => 'POST', 'id' => 'invoice_from','class'=>'form-horizontal'] ) !!}

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="col-md-8">

                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label"> 生产状态：</label>
                    <div class="col-sm-9">
                        <select name="status" class="form-control">
                            @foreach($status as $key => $item)
                                <option value="{{$key}}" {{$order->produce_status == $key ? 'selected' : ''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label"> 备注：</label>
                    <div class="col-sm-9">
                        <textarea name="note" class="form-control"></textarea>
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






