@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>售后服务处理进度提醒</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.setting.wechat')!!}"><i class="fa fa-dashboard"></i> 模板消息设置 </a></li>
        <li class="active">售后服务处理进度提醒</li>
    </ol>
@endsection

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
@include('backend::wechat.include.script')
@section('content')
    <div class="tabs-container">
        <form method="post" action="{{route('admin.setting.wechat.save')}}" class="form-horizontal" id="setting_site_form">
            {{csrf_field()}}
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> 售后服务
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">售后服务</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="wechat_message_after_sales_service[status]" value="1" {{isset($service['status']) && $service['status']==1 ? 'checked' : (!isset($service['status']) ? 'checked' : '')}}>启用</label>
                                            <label><input type="radio" name="wechat_message_after_sales_service[status]" value="0" {{isset($service['status']) && $service['status']==0 ? 'checked' : ''}}>禁用</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">编号</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_after_sales_service[id]" value="{{isset($service['id']) && $service['id'] ? $service['id'] : ''}}" {{isset($service['id']) && $service['id'] ? 'readonly' : ''}} class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_after_sales_service[first]" value="{{isset($service['first']) && $service['first'] ? $service['first'] : '您好，您的售后单2905928有新的客服回复：'}}" class="form-control wechat_message_first_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">模版ID</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_after_sales_service[template_id]" value="{{isset($service['id']) && $service['template_id'] ? $service['template_id'] : ''}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">提示:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_after_sales_service[remark]" value="{{isset($service['remark']) && $service['remark'] ? $service['remark'] : '点击“详情”查看详细处理结果，如有疑问可回复KF联系'}}" class="form-control wechat_message_remark_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wx-template">
                                    <div class="wx-title">售后服务处理进度提醒</div>
                                    <div class="wx-date">8月8日</div>
                                    <div class="wx-content">
                                        <span class="wechat_message_first_text_target">{{isset($service['first']) && $service['first'] ? $service['first'] : '您好，您的售后单2905928有新的客服回复：'}}</span><br/><br/>
                                        服务类型：换货<br/>
                                        处理状态：待处理<br/>
                                        提交时间：2013-12-23 14:48:24<br/>
                                        当前进度：您好，订单是第三方物流配送，无法直接取消，已登物流人员拦截，若拦截不成功，您请直接拒收哦，货款已有客服…<br/><br/>

                                        <span class="wechat_message_remark_text_target">{{isset($service['remark']) && $service['remark'] ? $service['remark'] : '点击“详情”查看详细处理结果，如有疑问可回复KF联系'}}</span>
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
@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

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
@stop