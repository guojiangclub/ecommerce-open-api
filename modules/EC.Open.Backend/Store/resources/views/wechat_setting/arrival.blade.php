{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>到货提醒</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.setting.wechat')!!}"><i class="fa fa-dashboard"></i> 模板消息设置 </a></li>
        <li class="active">到货提醒</li>
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
                            <i class="fa fa-info-circle"></i> 到货提醒
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">到货提醒</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="wechat_message_arrival_of_goods[status]" value="1" {{ isset($arrival['status']) && $arrival['status'] == 1 ? 'checked' : (!isset($arrival['status']) ? 'checked' : '') }}>启用</label>
                                            <label><input type="radio" name="wechat_message_arrival_of_goods[status]" value="0" {{ isset($arrival['status']) && $arrival['status'] == 0 ? 'checked' : '' }}>禁用</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">编号</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_arrival_of_goods[id]" value="{{isset($arrival['id']) && $arrival['id'] ? $arrival['id'] : ''}}" {{isset($arrival['id']) && $arrival['id'] ? 'readonly' : ''}} class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_arrival_of_goods[first]" value="{{isset($arrival['first']) && $arrival['first'] ? $arrival['first'] : '您好。您订阅的商品已到货，请尽快查看。'}}" class="form-control wechat_message_first_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">模版ID</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_arrival_of_goods[template_id]" value="{{isset($arrival['template_id']) && $arrival['template_id'] ? $arrival['template_id'] : ''}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">提示:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="wechat_message_arrival_of_goods[remark]" value="{{isset($arrival['remark']) && $arrival['remark'] ? $arrival['remark'] : '点击查看订单详情'}}" class="form-control wechat_message_remark_text" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wx-template">
                                    <div class="wx-title">到货提醒</div>
                                    <div class="wx-date">8月8日</div>
                                    <div class="wx-content">
                                        <span class="wechat_message_first_text_target">{{isset($arrival['first']) && $arrival['first'] ? $arrival['first'] : '您好。您订阅的商品已到货，请尽快查看。'}}</span><br/><br/>
                                        订阅商品：Revue Thommen Herrenarmbanduhr Diver Professional 17030.2137<br/>
                                        商品价格：200元<br/>
                                        订阅时间：2014-11-11 10:12:12<br/>

                                        <span class="wechat_message_remark_text_target">{{isset($arrival['remark']) && $arrival['remark'] ? $arrival['remark'] : '点击查看订单详情'}}</span>
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