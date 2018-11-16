{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>发货通知</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.setting.wechat')!!}"><i class="fa fa-dashboard"></i> 模板消息设置 </a></li>
        <li class="active">发货通知</li>
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
                            <i class="fa fa-info-circle"></i> 发货通知
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">发货通知</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="wechat_message_deliver_goods_remind[status]" value="1" {{isset($deliver['status']) && $deliver['status']==1 ? 'checked' : (!isset($deliver['status']) ? 'checked' : '')}}>启用</label>
                                            <label><input type="radio" name="wechat_message_deliver_goods_remind[status]" value="0" {{isset($deliver['status']) && $deliver['status']==0 ? 'checked' : ''}}>禁用</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">编号</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_deliver_goods_remind[id]" value="{{isset($deliver['id']) &&  $deliver['id'] ? $deliver['id'] : '' }}" {{isset($deliver['id']) &&  $deliver['id'] ? 'readonly' : '' }} class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_deliver_goods_remind[first]" value="{{isset($deliver['first']) && $deliver['first'] ? $deliver['first'] : '你好，您购买的订单已发货！'}}" class="form-control wechat_message_first_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">模版ID</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_deliver_goods_remind[template_id]" value="{{isset($deliver['template_id']) && $deliver['template_id'] ? $deliver['template_id'] : ''}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">提示:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_deliver_goods_remind[remark]" value="{{isset($deliver['remark']) && $deliver['remark'] ? $deliver['remark'] : '点击查看订单详情'}}" class="form-control wechat_message_remark_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wx-template">
                                    <div class="wx-title">发货通知</div>
                                    <div class="wx-date">8月8日</div>
                                    <div class="wx-content">
                                        <span class="wechat_message_first_text_target">{{isset($deliver['first']) && $deliver['first'] ? $deliver['first'] : '你好，您购买的订单已发货！'}}</span><br/><br/>
                                        快递公司：韵达快递<br/>
                                        快递单号：987654321<br/>
                                        发货时间：2017年10月26日 17:50:50<br/>

                                        <span class="wechat_message_remark_text_target">{{isset($deliver['remark']) && $deliver['remark'] ? $deliver['remark'] : '点击查看订单详情'}}</span>
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