@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    生成优惠码
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    {!! Form::open( [ 'url' => [route('admin.promotion.coupon.createCouponCode')], 'method' => 'POST', 'id' => 'create-code-form','class'=>'form-horizontal'] ) !!}
    <div class="row">
        <div class="col-md-12">
            <label class="col-sm-2 control-label">生成数量：</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="number" placeholder="本次最多可生成 {{$limit}} 个优惠码">
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-9">
                <button type="button" class="btn btn-primary export-goods" data-toggle="modal-filter"
                        data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                        data-link="{{route('admin.promotion.coupon.getExportData',['type'=>'xls','discount_id'=>$discount_id])}}" id="coupon_export"
                        data-url="{{route('admin.export.index',['toggle'=>'coupon_export'])}}"
                        data-type="xls"
                >导出已生成的优惠码
                </button>
            </div>
        </div>


        <input type="hidden" name="discount_id" value="{{$discount_id}}">

    </div>

    {!! Form::close() !!}
@stop

{!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}


@section('footer')

    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit" id="sub-btn" class="btn btn-primary" data-toggle="form-submit"
            data-target="#create-code-form">确定
    </button>

    <script>
        $('#create-code-form').ajaxForm({
            beforeSubmit: function () {
                $('#sub-btn').text('正在生成');
                $('#sub-btn').attr('disabled', true);
            },
            success: function (result) {
                if (!result.status) {
                    swal("生成失败!", result.message, "error")
                } else {
                    swal({
                        title: "生成成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                }

            }

        });

        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var $this = $(this),
                    href = $this.attr('href'),
                    modalUrl = $(this).data('url');

            {{--var url = '{{route('admin.promotion.coupon.getExportData')}}';--}}
            {{--var type = $(this).data('type');--}}

            {{--url = url + '?type=' + type;--}}
            {{--$(this).data('link', url);--}}

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(modalUrl, function () {

                });
            }
        });
    </script>
@stop






