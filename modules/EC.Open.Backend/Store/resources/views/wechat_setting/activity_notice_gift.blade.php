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
                        <i class="fa fa-info-circle"></i> 礼品领取成功通知
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">礼品领取成功</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="wechat_message_activity_notice_gift[status]"
                                                      value="1" {{isset($activity_notice_gift['status']) && $activity_notice_gift['status']==1 ? 'checked' : (!isset($activity_notice_gift['status']) ? 'checked' : '')}}>启用</label>
                                        <label><input type="radio" name="wechat_message_activity_notice_gift[status]"
                                                      value="0" {{isset($activity_notice_gift['status']) && $activity_notice_gift['status']==0 ? 'checked' : ''}}>禁用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">编号</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice_gift[id]"
                                           value="{{isset($activity_notice_gift['id']) && $activity_notice_gift['id'] ? $activity_notice_gift['id'] : ''}}"
                                           {{isset($activity_notice_gift['id']) && $activity_notice_gift['id'] ? 'readonly' : ''}} class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice_gift[first]"
                                           value="{{isset($activity_notice_gift['first']) && $activity_notice_gift['first'] ? $activity_notice_gift['first'] : '礼品领取成功'}}"
                                           class="form-control wechat_message_first_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模版ID</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice_gift[template_id]"
                                           value="{{isset($activity_notice_gift['template_id']) && $activity_notice_gift['template_id'] ? $activity_notice_gift['template_id'] : ''}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">提示:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice_gift[remark]"
                                           value="{{isset($activity_notice_gift['remark']) && $activity_notice_gift['remark'] ? $activity_notice_gift['remark'] : '1000元装备基金已到手！戳此立即领取～'}}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">URL:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice_gift[url]"
                                           value="{{isset($activity_notice_gift['url']) && $activity_notice_gift['url'] ? $activity_notice_gift['url'] :app('system_setting')->getSetting('mobile_domain_url') }}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="wx-template">
                                <div class="wx-title">礼品领取成功通知</div>
                                <div class="wx-date">8月8日</div>
                                <div class="wx-content">
                                    <span class="wechat_message_first_text_target">{{isset($activity_notice_gift['first']) && $activity_notice_gift['first'] ? $activity_notice_gift['first'] : '礼品领取成功'}}</span><br/><br/>

                                    领取人：ibrand<br>
                                    礼品：狼爪1000元装备基金<br>
                                    领取时间：2018-10-1 18:00:00<br>


                                    <span class="wechat_message_remark_text_target">{{isset($activity_notice_gift['remark']) && $activity_notice_gift['remark'] ? $activity_notice_gift['remark'] : '1000元装备基金已到手！戳此立即领取～'}}</span>
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