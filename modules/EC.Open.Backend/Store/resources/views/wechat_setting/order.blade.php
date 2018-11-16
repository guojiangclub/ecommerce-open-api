{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>订单付款提醒</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.setting.wechat')!!}"><i class="fa fa-dashboard"></i> 模板消息设置 </a></li>
        <li class="active">订单付款提醒</li>
    </ol>
@endsection--}}

<style>
    .wx-template {
        display: inline-block;
        width: 300px;
        margin: 15px;
        padding: 15px 15px 10px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        font-size: 14px;
    }

    .wx-title {
        position: relative;
        margin-bottom: 6px;
        font-size: 20px;
        font-weight: bold;
    }

    .wx-date {
        margin-bottom: 20px;
        font-size: 14px;
        color: #999;
    }

    .wx-content {
        margin-bottom: 20px;
    }

    .wx-link {
        position: relative;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
</style>
@include('store-backend::wechat_setting.include.script')
{{--@section('content')--}}
    <div class="tabs-container">
        <form method="post" action="{{route('admin.setting.wechat.save')}}" class="form-horizontal" id="setting_site_form">
            {{csrf_field()}}
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> 订单付款提醒
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">订单催付</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="wechat_message_order_pay_remind[status]" value="1" {{isset($order['status']) && $order['status']==1 ? 'checked' : (!isset($order['status']) ? 'checked' : '')}}>启用</label>
                                            <label><input type="radio" name="wechat_message_order_pay_remind[status]" value="0" {{isset($order['status']) && $order['status']==0 ? 'checked' : ''}}>禁用</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">编号</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_order_pay_remind[id]" value="{{isset($order['id']) && $order['id'] ? $order['id'] : ''}}" {{isset($order['id']) && $order['id'] ? 'readonly' : ''}} class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_order_pay_remind[first]" value="{{isset($order['first']) && $order['first'] ? $order['first'] : '您好，15分钟内未付款订单将自动关闭。'}}" class="form-control wechat_message_first_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">模版ID</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_order_pay_remind[template_id]" value="{{isset($order['template_id']) && $order['template_id'] ? $order['template_id'] : ''}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">提示:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_order_pay_remind[remark]" value="{{isset($order['remark']) && $order['remark'] ? $order['remark'] : '点击查看订单详情'}}" class="form-control wechat_message_remark_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wx-template">
                                    <div class="wx-title">订单付款提醒</div>
                                    <div class="wx-date">8月8日</div>
                                    <div class="wx-content">
                                        <span class="wechat_message_first_text_target">{{isset($order['first']) && $order['first'] ? $order['first'] : '您好，15分钟内未付款订单将自动关闭。'}}</span><br/><br/>
                                        订单编号：O2017102611202356<br/>
                                        订单金额：110元<br/>
                                        下单时间：2014年7月21日 18:36<br/>
                                        商品数量：3<br/>

                                        <span class="wechat_message_remark_text_target">{{isset($order['remark']) && $order['remark'] ? $order['remark'] : '点击查看订单详情'}}</span>
                                    </div>
                                    <div class="wx-link">详情</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox-content m-b-sm border-bottom text-center">
                    <button class="btn btn-primary" type="submit">保存设置</button>
                </div>
            </div>
        </form>
    </div>
{{--@endsection

@section('after-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        window.location = '{{route('admin.setting.wechat')}}';
                    });
                }
            });
        })
    </script>
{{--@stop--}}