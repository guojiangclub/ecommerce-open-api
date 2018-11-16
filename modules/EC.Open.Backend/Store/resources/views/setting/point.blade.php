{{--@extends('store-backend::dashboard')

@section ('title','积分设置')

@section('breadcrumbs')
    <h2>商城设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">积分设置</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveShopSetting')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">是否启用积分系统：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="point_enabled" {{settings('point_enabled') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="point_enabled" {{!settings('point_enabled') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">积分有效天数(单位:天)：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control valid-num"
                                   value="{{settings('point_valid_time_setting')?settings('point_valid_time_setting'):0}}"
                                   name="point_valid_time_setting" placeholder="">
                        </label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">开启订单使用积分<i class="fa fa-question-circle"
                                                                     data-toggle="tooltip" data-placement="top"
                                                                     data-original-title="开启后在下单时可使用积分"></i>：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="order_use_point_enabled" {{settings('order_use_point_enabled') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="order_use_point_enabled" {{!settings('order_use_point_enabled') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">积分折扣比例(单位:分)<i class="fa fa-question-circle"
                                                                         data-toggle="tooltip" data-placement="top"
                                                                         data-original-title="举例：如果值设为10，表示1个积分抵扣10分钱 "></i>：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control valid-num"
                                   value="{{settings('point_proportion')?settings('point_proportion'):0}}"
                                   name="point_proportion" placeholder="">
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">积分折扣占订单金额上限(单位:%)：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control valid-num"
                                   value="{{settings('point_order_limit')?settings('point_order_limit'):0}}"
                                   name="point_order_limit" placeholder="">
                        </label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商品是否默认赠送积分<i class="fa fa-question-circle"
                                                                       data-toggle="tooltip" data-placement="top"
                                                                       data-original-title="启用该功能时，所有商品默认参与送积分。优先级：促销活动送积分 > 商品本身积分规则 > 本规则 "></i>：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="point_goods_enabled" {{settings('point_goods_enabled') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="point_goods_enabled" {{!settings('point_goods_enabled') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">销售价低于吊牌价指定折扣无法获得积分(单位:%)：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control valid-num"
                                   value="{{settings('point_invalid_ratio')?settings('point_invalid_ratio'):70}}"
                                   name="point_invalid_ratio" placeholder="">
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">参与积分赠送比例(单位:%)：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control valid-num"
                                   value="{{settings('point_goods_ratio')?settings('point_goods_ratio'):100}}"
                                   name="point_goods_ratio" placeholder="">
                        </label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">开启积分生效定时任务：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="point_apply_status" {{settings('point_apply_status') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="point_apply_status" {{!settings('point_apply_status') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label">套餐订单是否能够使用积分：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="point_suit_enabled" {{settings('point_suit_enabled') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="point_suit_enabled" {{!settings('point_suit_enabled') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">H5积分规则链接：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="text" class="form-control"
                                   value="{{settings('point_rule_h5_url')}}"
                                   name="point_rule_h5_url" placeholder="">
                        </label>
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
{{--
@endsection

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