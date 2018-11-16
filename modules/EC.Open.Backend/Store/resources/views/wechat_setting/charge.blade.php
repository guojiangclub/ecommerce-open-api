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
                        <i class="fa fa-info-circle"></i> 充值成功提醒
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">充值成功提醒</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="wechat_message_charge_success[status]"
                                                      value="1" {{isset($charge['status']) && $charge['status']==1 ? 'checked' : (!isset($charge['status']) ? 'checked' : '')}}>启用</label>
                                        <label><input type="radio" name="wechat_message_charge_success[status]"
                                                      value="0" {{isset($charge['status']) && $charge['status']==0 ? 'checked' : ''}}>禁用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">编号</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_charge_success[id]"
                                           value="{{isset($charge['id']) && $charge['id'] ? $charge['id'] : ''}}"
                                           {{isset($charge['id']) && $charge['id'] ? 'readonly' : ''}} class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_charge_success[first]"
                                           value="{{isset($charge['first']) && $charge['first'] ? $charge['first'] : '尊敬的顾客，您已充值成功。'}}"
                                           class="form-control wechat_message_first_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模版ID</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_charge_success[template_id]"
                                           value="{{isset($charge['id']) && $charge['template_id'] ? $charge['template_id'] : ''}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">提示:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_charge_success[remark]"
                                           value="{{isset($charge['remark']) && $charge['remark'] ? $charge['remark'] : '充值1000元送100元，详情请咨询客服，谢谢'}}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="wx-template">
                                <div class="wx-title">充值成功提醒</div>
                                <div class="wx-date">8月8日</div>
                                <div class="wx-content">
                                    <span class="wechat_message_first_text_target">{{isset($charge['first']) && $charge['first'] ? $charge['first'] : '尊敬的顾客，您已充值成功。'}}</span><br/><br/>
                                    充值金额：1000元<br/>
                                    充值时间：2015年09月10日15:60<br/>
                                    账户余额：1100元<br/>
                                    <span class="wechat_message_remark_text_target">{{isset($charge['remark']) && $charge['remark'] ? $charge['remark'] : '充值1000元送100元，详情请咨询客服，谢谢'}}</span>
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