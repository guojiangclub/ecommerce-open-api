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
                        <i class="fa fa-info-circle"></i> 活动结果通知
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">活动结果通知</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="wechat_message_activity_notice[status]"
                                                      value="1" {{isset($activity_notice['status']) && $activity_notice['status']==1 ? 'checked' : (!isset($activity_notice['status']) ? 'checked' : '')}}>启用</label>
                                        <label><input type="radio" name="wechat_message_activity_notice[status]"
                                                      value="0" {{isset($activity_notice['status']) && $activity_notice['status']==0 ? 'checked' : ''}}>禁用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">编号</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice[id]"
                                           value="{{isset($activity_notice['id']) && $activity_notice['id'] ? $activity_notice['id'] : ''}}"
                                           {{isset($activity_notice['id']) && $activity_notice['id'] ? 'readonly' : ''}} class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice[first]"
                                           value="{{isset($activity_notice['first']) && $activity_notice['first'] ? $activity_notice['first'] : '恭喜您成为狼爪#冬日极客#！'}}"
                                           class="form-control wechat_message_first_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模版ID</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice[template_id]"
                                           value="{{isset($activity_notice['template_id']) && $activity_notice['template_id'] ? $activity_notice['template_id'] : ''}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">提示:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_activity_notice[remark]"
                                           value="{{isset($activity_notice['remark']) && $activity_notice['remark'] ? $activity_notice['remark'] : '戳此查看您的狼旗照片～'}}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="wx-template">
                                <div class="wx-title">活动结果通知</div>
                                <div class="wx-date">8月8日</div>
                                <div class="wx-content">
                                    <span class="wechat_message_first_text_target">{{isset($activity_notice['first']) && $activity_notice['first'] ? $activity_notice['first'] : '恭喜您成为狼爪#冬日极客#！'}}</span><br/><br/>

                                    活动名称：不怕冷，自然不错过-冬日极客招募<br>
                                    活动结果：您的照片成功入选，并获得1000元装备基金<br>
                                    活动时间：2018-10-1 18:00:00<br>
                                    会员名称：ibrand<br>

                                    <span class="wechat_message_remark_text_target">{{isset($activity_notice['remark']) && $activity_notice['remark'] ? $activity_notice['remark'] : '戳此查看您的狼旗照片～'}}</span>
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