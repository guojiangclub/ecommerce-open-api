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

<div class="tabs-container">
    <form method="post" action="{{route('admin.setting.wechat.save')}}" class="form-horizontal" id="setting_site_form">
        {{csrf_field()}}
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> 拼团失败提醒
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">拼团失败提醒</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="wechat_message_groupon_failed[status]"
                                                      value="1" {{isset($grouponFailed['status']) && $grouponFailed['status']==1 ? 'checked' : (!isset($grouponFailed['status']) ? 'checked' : '')}}>启用</label>
                                        <label><input type="radio" name="wechat_message_groupon_failed[status]"
                                                      value="0" {{isset($grouponFailed['status']) && $grouponFailed['status']==0 ? 'checked' : ''}}>禁用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">编号</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_failed[id]"
                                           value="{{isset($grouponFailed['id']) && $grouponFailed['id'] ? $grouponFailed['id'] : ''}}"
                                           {{isset($grouponFailed['id']) && $grouponFailed['id'] ? 'readonly' : ''}} class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_failed[first]"
                                           value="{{isset($grouponFailed['first']) && $grouponFailed['first'] ? $grouponFailed['first'] : '您好，您参加的拼团由于团已过期，拼团失败。'}}"
                                           class="form-control wechat_message_first_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模版ID</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_failed[template_id]"
                                           value="{{isset($grouponFailed['template_id']) && $grouponFailed['template_id'] ? $grouponFailed['template_id'] : ''}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">提示:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_failed[remark]"
                                           value="{{isset($grouponFailed['remark']) && $grouponFailed['remark'] ? $grouponFailed['remark'] : '您的退款已经提交审核，感谢您的参与！'}}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="wx-template">
                                <div class="wx-title">拼团失败提醒</div>
                                <div class="wx-date">8月8日</div>
                                <div class="wx-content">
                                    <span class="wechat_message_first_text_target">{{isset($grouponFailed['first']) && $grouponFailed['first'] ? $grouponFailed['first'] : '您好，您参加的拼团由于团已过期，拼团失败。'}}</span><br/><br/>

                                    拼团商品：精选新西兰猕猴桃6枚<br>
                                    商品金额：￥10<br>
                                    退款金额：￥10<br>

                                    <span class="wechat_message_remark_text_target">{{isset($grouponFailed['remark']) && $grouponFailed['remark'] ? $grouponFailed['remark'] : '您的退款已经提交审核，感谢您的参与！'}}</span>
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