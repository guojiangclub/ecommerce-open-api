{{--@extends('store-backend::dashboard')

@section ('title','价格保护设置')

@section('breadcrumbs')
    <h2>价格保护设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">价格保护设置</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveShopSetting')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}
                <div class="form-group">

                    <label class="col-sm-2 control-label">自动下架保护：</label>

                    <div class="col-sm-8">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="goods_price_protection_enabled" {{settings('goods_price_protection_enabled') ? 'checked': ''}}>
                            启用
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="goods_price_protection_enabled" {{!settings('goods_price_protection_enabled') ? 'checked': ''}}>
                            禁用
                        </label>
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">自动下架折扣 <i class="fa fa-question-circle"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    data-original-title="设置SKU价格时，当SKU价格低于吊牌价指定折扣价格将自动下架商品，避免因为价格错误出售"></i>：</label>

                    <div class="col-sm-3">
                        <div class="input-group m-b">
                            <input class="form-control valid-num" type="text" name="goods_price_protection_discount_percentage"
                                   value="{{settings('goods_price_protection_discount_percentage')?settings('goods_price_protection_discount_percentage'):30}}">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">订单自动保护 <i class="fa fa-question-circle"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    data-original-title="当订单价格异常时自动关闭订单"></i>：</label>

                    <div class="col-sm-8">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="order_price_protection_enabled" {{settings('order_price_protection_enabled') ? 'checked': ''}}>
                            启用
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="order_price_protection_enabled" {{!settings('order_price_protection_enabled') ? 'checked': ''}}>
                            禁用
                        </label>
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">订单保护折扣 <i class="fa fa-question-circle"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    data-original-title="当销售价低于吊牌价多少折扣时自动设置订单为无效订单"></i>：</label>

                    <div class="col-sm-3">
                        <div class="input-group m-b">
                            <input class="form-control valid-num" type="text" name="order_price_protection_discount_percentage"
                                   value="{{settings('order_price_protection_discount_percentage')?settings('order_price_protection_discount_percentage'):30}}">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>


                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存设置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $('.valid-num').bind('input propertychange', function (e) {
            var value = $(e.target).val()
            if (!/^[-]?[0-9]*\.?[0-9]+(eE?[0-9]+)?$/.test(value)) {
                value = value.replace(/[^\d.].*$/, '');
                $(e.target).val(value);
            } else if (value.indexOf('-') != -1) {
                $(e.target).val('');
            }
        });

        $(function () {
            $('#setting_site_form').find("input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });


            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });

                }
            });
        })
    </script>
{{--@stop--}}