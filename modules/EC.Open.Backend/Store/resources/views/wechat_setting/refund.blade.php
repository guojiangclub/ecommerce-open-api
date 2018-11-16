{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>退货结果通知</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.setting.wechat')!!}"><i class="fa fa-dashboard"></i> 模板消息设置 </a></li>
        <li class="active">退货结果通知</li>
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
                            <i class="fa fa-info-circle"></i> 退货结果通知
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">退货结果通知</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="wechat_message_goods_refund_result[status]" value="1" {{isset($refund['status']) && $refund['status']==1 ? 'checked' : (!isset($refund['status']) ? 'checked' : '')}}>启用</label>
                                            <label><input type="radio" name="wechat_message_goods_refund_result[status]" value="0" {{isset($refund['status']) && $refund['status']==0 ? 'checked' : ''}}>禁用</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">编号</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_goods_refund_result[id]" value="{{isset($refund['id']) && $refund['id'] ? $refund['id'] : ''}}" {{isset($refund['id']) && $refund['id'] ? 'readonly' : ''}} class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_goods_refund_result[first]" value="{{isset($refund['first']) && $refund['first'] ? $refund['first'] : '您好，商家已收到您的退货，将在7个工作日内退款。'}}" class="form-control wechat_message_first_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">模版ID</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_goods_refund_result[template_id]" value="{{isset($refund['id']) && $refund['template_id'] ? $refund['template_id'] : ''}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">提示:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_goods_refund_result[remark]" value="{{isset($refund['remark']) && $refund['remark'] ? $refund['remark'] : '点击查看工单详情'}}" class="form-control wechat_message_remark_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wx-template">
                                    <div class="wx-title">退货结果通知</div>
                                    <div class="wx-date">8月8日</div>
                                    <div class="wx-content">
                                        <span class="wechat_message_first_text_target">{{isset($refund['first']) && $refund['first'] ? $refund['first'] : '您好，商家已收到您的退货，将在7个工作日内退款。'}}</span><br/><br/>
                                        订单号：O201710310923<br/>
                                        售后工单号：O201711010923<br/>
                                        申请时间：2017年11月1日<br/>
                                        售后原因：七天无理由<br/>
                                        <span class="wechat_message_remark_text_target">{{isset($refund['remark']) && $refund['remark'] ? $refund['remark'] : '点击查看工单详情'}}</span>
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