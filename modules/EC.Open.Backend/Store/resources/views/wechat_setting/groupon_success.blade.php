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
                        <i class="fa fa-info-circle"></i> 拼团成功提醒
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">拼团成功提醒</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="wechat_message_groupon_success[status]"
                                                      value="1" {{isset($grouponSuccess['status']) && $grouponSuccess['status']==1 ? 'checked' : (!isset($grouponSuccess['status']) ? 'checked' : '')}}>启用</label>
                                        <label><input type="radio" name="wechat_message_groupon_success[status]"
                                                      value="0" {{isset($grouponSuccess['status']) && $grouponSuccess['status']==0 ? 'checked' : ''}}>禁用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">编号</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_success[id]"
                                           value="{{isset($grouponSuccess['id']) && $grouponSuccess['id'] ? $grouponSuccess['id'] : ''}}"
                                           {{isset($grouponSuccess['id']) && $grouponSuccess['id'] ? 'readonly' : ''}} class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_success[first]"
                                           value="{{isset($grouponSuccess['first']) && $grouponSuccess['first'] ? $grouponSuccess['first'] : '您参团的商品［双十一提前惠湖南冰糖桔提前抢］已组团成功。'}}"
                                           class="form-control wechat_message_first_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模版ID</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_success[template_id]"
                                           value="{{isset($grouponSuccess['template_id']) && $grouponSuccess['template_id'] ? $grouponSuccess['template_id'] : ''}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">提示:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="wechat_message_groupon_success[remark]"
                                           value="{{isset($grouponSuccess['remark']) && $grouponSuccess['remark'] ? $grouponSuccess['remark'] : '点击查看订单详情'}}"
                                           class="form-control wechat_message_remark_text" oninput="OnInput(event)"
                                           onpropertychange="OnPropChanged(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="wx-template">
                                <div class="wx-title">拼团成功提醒</div>
                                <div class="wx-date">8月8日</div>
                                <div class="wx-content">
                                    <span class="wechat_message_first_text_target">{{isset($grouponSuccess['first']) && $grouponSuccess['first'] ? $grouponSuccess['first'] : '您参团的商品［双十一提前惠湖南冰糖桔提前抢］已组团成功。'}}</span><br/><br/>

                                    商品价格：210元<br>
                                    订单号：2015081175685<br>
                                    <span class="wechat_message_remark_text_target">{{isset($grouponSuccess['remark']) && $grouponSuccess['remark'] ? $grouponSuccess['remark'] : '点击查看订单详情'}}</span>
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