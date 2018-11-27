@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop

@section('title')
    {{ isset($deliver) ? '编辑' : '' }}订单发货
@stop

@section('body')
    <div class="row">
        <form method="POST" action="{{route('admin.orders.savedeliver')}}" accept-charset="UTF-8" id="delivers_from" class="form-horizontal">
            <input type="hidden" name="order_id" value="{{ !empty($order_id) ? $order_id : '' }}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">快递名称：</label>
                    <div class="col-sm-9">
                        @if($freightCompany->count()>0)
                            <select class="form-control" name="method_id">
                                <option value="">请选择快递方式</option>
                                @foreach($freightCompany as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">快递单号：</label>
                    <div class="col-sm-9">
                        <input type="text" name="tracking" value="" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">发货时间：</label>
                    <div class="col-sm-9">
                        <div class="date form_datetime">
                            <input type="text" class="form-control" name="delivery_time"
                                   value="{{date("Y-m-d H:i:s",time())}}" placeholder="" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@stop

{!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#delivers_from">保存
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


        $('#delivers_from').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = decodeURIComponent('{{$redirect_url}}');
                    });
                }

            }

        });
    </script>
@stop