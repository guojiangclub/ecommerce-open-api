@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    修改收货地址
@stop

@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.orders.postAddress')}}" accept-charset="UTF-8"
              id="base-form" class="form-horizontal">

            <input type="hidden" name="order_id" value="{{$order->id}}">

            <div class="form-group">
                {!! Form::label('name','收货人：', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" value="{{$order->accept_name}}" name="accept_name"
                           placeholder="" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','电话：', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" value="{{$order->mobile}}" name="mobile" placeholder=""
                           required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','地址：', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-9" id="edit-address">
                    <div class="col-sm-4">
                        <select class="form-control" name="province"></select><!-- 省 -->
                    </div>

                    <div class="col-sm-4">
                        <select class="form-control" name="city"></select><!-- 市 -->
                    </div>

                    <div class="col-sm-4">
                        <select class="form-control" name="district"></select><!-- 区 -->
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','详细地址：', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" name="address" value="{{$order->address}}" placeholder="">
                </div>
            </div>
        </form>
    </div>
@stop

{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/distpicker.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit" class="btn btn-primary" data-style="slide-right" data-toggle="form-submit"
            data-target="#base-form">保存
    </button>

    <script>
        $(function () {
            $('#edit-address').distpicker({
                province: '{{$address[0]}}',
                city: '{{$address[1]}}',
                district: '{{$address[2]}}'
            });
        });


        $(document).ready(function () {
            $('#base-form').ajaxForm({
                success: function (result) {
                    if (result.status) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal("保存失败!", result.message, "error")
                    }
                }
            });
        });
    </script>
@stop






