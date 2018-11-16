{{--@extends('store-backend::dashboard')

@section ('title','定向发券活动')


@section('after-styles-end')--}}
{!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
<style>
    #alert-box {
        position: fixed;
        top: 0;
        bottom: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, .4);
        z-index: 99999999 !important;
    }

    #alert-box i {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
    }
</style>
{{--@stop

@section ('breadcrumbs')
    <h2>创建定向发券活动</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
    </ol>
@stop



@section('content')--}}
<div class="tabs-container">
    @if (session()->has('flash_notification.message'))
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {!! session('flash_notification.message') !!}
        </div>
    @endif

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 定向发券活动</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <div class="row">
                    {!! Form::open( [ 'url' => [route('admin.promotion.directional.searchUser')], 'method' => 'POST', 'id' => 'create-directional-coupon-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*活动名称:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" placeholder=""/>
                        </div>
                    </div>

                    <div class="form-group" style="display: none">
                        <label class="col-sm-2 control-label">赠送优惠券</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="coupon_title" name="coupon_title"
                                   placeholder="请输入进行中的优惠券名称搜索"/>
                        </div>
                        <div class="col-sm-2">
                            <a href="javascript:;" class="btn btn-w-m btn-info" id="search">搜索</a>
                        </div>
                    </div>


                    <div class="form-group" style="">
                        <label class="col-sm-2 control-label">*选择人群：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input id="for_phone" name="directional_type"
                                                                           type="radio"
                                                                           value="mobile">
                                注册手机号</label>
                            <br>
                            <br>

                            <div id="box_1" style="display:none">
                                <div class="col-sm-6">
                                    <textarea class="form-control" rows="8" name="mobile"></textarea>
                                </div>
                                <div class="col-sm-4">
                                    一次最多支持200个手机号，多个手机号用#分隔.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group" style="">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input id="custom_radio" name="directional_type"
                                                                           type="radio"
                                                                           value="custom">
                                自定义</label>
                            <br>
                            <br>
                            <div id="box_2" style="display:none">
                                @if(count($groups)>0)
                                    <div class="col-sm-4">
                                        <select class="form-control" name="">
                                            <option value="">会员等级</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="group_id">
                                            <option value="">请选择会员等级</option>
                                            @foreach($groups as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                @else
                                    <input type="hidden" name="group_id" value="">
                                @endif


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">N天内有购买</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control num" name="n_day_buy" placeholder="天数"/>
                                    </div>

                                </div>
                                <br>
                                <br>
                                <br>


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">N天内有无购买</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control num" name="n_day_no_buy"
                                               placeholder="天数"/>
                                    </div>

                                </div>
                                <br>
                                <br>
                                <br>


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">购买订单价格大于等于</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control num" name="buy_price_above"
                                               placeholder="元"/>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">购买订单价格小于等于</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control num" name="buy_price_below"
                                               placeholder="元"/>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">累计购物订单次数大于等于</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control num" name="buy_num_above" placeholder="次数"/>
                                </div>
                                <br>
                                <br>
                                <br>


                                <div class="col-sm-4">
                                    <select class="form-control" name="">
                                        <option value="">累计购物订单次数小于等于</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control num" name="buy_num_below" placeholder="次数"/>
                                </div>
                                <br>
                                <br>
                                <br>

                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">*赠送优惠券:</label>
                        <div class="col-sm-8">
                            <select class="form-control select_coupon" name="coupon_id">
                                <option id="option_coupon" value="">请选择优惠券</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">*发送人数:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="number" value="0" placeholder="" disabled/>
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
        </div>
    </div>
</div>


{{--@stop--}}

<div id="alert-box" style="display: none">
    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
</div>

{{--@section('after-scripts-end')--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
{{--    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}

<script>
    var coupon_api = "{{route('admin.promotion.directional.coupon.api.coupon',['status'=>'ing'])}}"
</script>
<script>

    $('.num').bind('input propertychange', function (e) {
        var value = $(e.target).val()
        if (!/^[-]?[0-9]*\?[0-9]+(eE?[0-9]+)?$/.test(value)) {
            value = value.replace(/[^\d]*$/, '');
            $(e.target).val(value);
        }
    })


    $('#for_phone').on('ifChecked', function (event) {
        $('#box_1').show();
        $('#box_2').hide();
        $('#box_2 input').val('');
    });

    $('#custom_radio').on('ifChecked', function (event) {
        $('#box_2').show();
        $('#box_1').hide();
        $('textarea[name=mobile]').val('');
    });


    $('#search').click(function () {
        var url = coupon_api;
        var coupon_title = $('#coupon_title').val();
        if (coupon_title !== '') url = url + "&" + 'title=' + coupon_title;
        $('.select_coupon').html('');
        $.get(url, function (res) {
            if (res.status) {
                var data = res.data;
                var html = "<option value=''>" + "请选择优惠券" + "</option>";
                if (data.length > 0) {
                    $.each(data, function (k, v) {
                        html += "<option value=" + v.id + ">" + v.title + "</option>";
                    })
                } else {
                    var html = "<option value=''>" + "无进行中的优惠券" + "</option>";
                }


                $('.select_coupon').html(html);
            }
        })
    });
    $('#search').trigger("click");

    $('#create-directional-coupon-form').ajaxForm({
        beforeSubmit: function (data) {
            var input = [];
            $.each(data, function (k, v) {
                if (v.name !== "lenght") {
                    input[v.name] = v.value;
                }
            })
            if (!input['name']) {
                swal({title: "保存失败", text: "活动名称不能为空", type: "error"});
                return false;
            }
            if (!input['directional_type']) {
                swal({title: "保存失败", text: "请选择人群", type: "error"});
                return false;
            }
            if (input['directional_type'] == 'mobile') {
                if (!input['mobile']) {
                    swal({title: "保存失败", text: "请输入注册手机号 ", type: "error"});
                    return false;
                }
            }
            if (input['directional_type'] == 'custom') {

                if (input['n_day_buy'].indexOf("0") == 0
                        || input['n_day_buy'].indexOf("0") == 0
                        || input['buy_num_below'].indexOf("0") == 0
                        || input['buy_num_above'].indexOf("0") == 0
                        || input['buy_price_above'].indexOf("0") == 0
                        || input['buy_price_below'].indexOf("0") == 0

                ) {
                    swal({title: "保存失败", text: "自定义条件不能为0", type: "error"});
                    return false;
                }


                if (input['n_day_buy'] && input['n_day_no_buy']) {
                    if (parseInt(input['n_day_buy']) <= parseInt(input['n_day_no_buy'])) {
                        swal({title: "保存失败", text: "N天内购买不能小于等于N天内无购买 ", type: "error"});
                        return false;
                    }
                }


                if (input['buy_num_above'] && input['buy_num_below']) {
                    if (parseInt(input['buy_num_above']) > parseInt(input['buy_num_below'])) {
                        var str = "购买大于" + parseInt(input['buy_num_above']) + "小于" + parseInt(input['buy_num_below']) + "的整数不存在";
                        swal({title: "保存失败", text: str, type: "error"});
                        return false;
                    }
                }

                if (input['buy_price_above'] && input['buy_price_below']) {
                    if (parseInt(input['buy_price_above']) > parseInt(input['buy_price_below'])) {
                        var str = "订单价格大于" + parseInt(input['buy_price_above']) + "小于" + parseInt(input['buy_price_below']) + "的整数不存在";
                        swal({title: "保存失败", text: str, type: "error"});
                        return false;
                    }
                }

            }

            if (!input['coupon_id']) {
                swal({title: "保存失败", text: "请选择优惠券", type: "error"});
                return false;
            }

            $('#alert-box').show();

        },
        success: function (res) {
            $('#alert-box').hide();
            if (res.data.num > 0) {
                $('input[name=number]').val(res.data.num);
                click_s(res.data.num, res.data.input);
            } else {
                swal({title: "保存失败", text: "当前条件下共筛选出0个用户", type: "error"});
            }
        }
    });


    function click_s(num, input) {
        swal({
                    title: "确定发送优惠券么？",
                    text: "共筛选出" + num + "个用户,发送人数低于" + num + "将随机发放",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "发送优惠券",
                    cancelButtonText: "取消",
                    animation: "slide-from-top",
                    inputPlaceholder: "请输入发送人数（最大" + num + ",最小1" + ")"
                },
                function (inputValue) {
                    if (inputValue === false) ;

                    if (inputValue === "") {
                        swal.showInputError("请输入发送人数（最大" + num + ")");
                        return false
                    }
                    var r = /^\+?[1-9][0-9]*$/;
                    r.test(inputValue);
                    if (!r.test(inputValue)) {
                        swal.showInputError("非正整数");
                        return false
                    }
                    if (parseInt(inputValue) > num || parseInt(inputValue) < 1) {
                        swal.showInputError("发送人数最大" + num + ",最小1");
                        return false
                    }
                    if (inputValue === "") {
                        swal.showInputError("请输入发送人数（最大" + num + ")");
                        return false
                    }

                    input.number = parseInt(inputValue);
                    input._token = _token;
                    var url = "{{route('admin.promotion.directional.coupon.store')}}";
                    $('#alert-box').show();
                    $.post(url, input, function (ret) {
                        $('#alert-box').hide();
                        if (!ret.status) {
                            swal("保存失败!", "", "warning");
                        } else {
                            swal({
                                title: "保存成功",
                                text: "",
                                type: "success",
                                confirmButtonText: "确定"
                            }, function () {
                                location.href = "{{route('admin.promotion.directional.coupon.index')}}";

                            });
                        }
                    });

                });
    }

</script>
{{--@stop--}}