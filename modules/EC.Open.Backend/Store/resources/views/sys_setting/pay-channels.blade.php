<style type="text/css">
    .pay-table tbody > tr > td {
        border: none;
        padding: 20px 8px;
        cursor: pointer;
    }

    .pay-table tbody > tr > td img {
        width: 20px;
        margin-right: 15px
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="tabs.html#tab-1" aria-expanded="true">通用设置</a></li>
        <li class=""><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="true">支付渠道设置</a></li>
    </ul>
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <form method="post" action="{{route('admin.setting.pay')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付场景 <i
                                    class="fa fa-question-circle"></i></label>
                        <div class="col-sm-6">
                            <input type="radio" value="live" name="pay_scene"
                                    {{settings('pay_scene') == 'live'? 'checked':''}}> live &nbsp;&nbsp;
                            <input type="radio" value="test" name="pay_scene"
                                    {{settings('pay_scene') == 'test'? 'checked':''}}> test
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary" type="submit">保存设置</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="tab-2" class="tab-pane">
            <div class="panel-body">
                <div class="box-body table-responsive">
                    <table class="table table-hover pay-table">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>支付渠道</th>
                            <th>应用场景</th>
                            <th>状态</th>
                        </tr>
                        <!--tr-th end-->
                        <tr onclick="location.href='{{route('admin.setting.editPayChannels',['type'=>'alipay_wap'])}}'">
                            <td><img src="/assets/backend/images/alipay_icon.jpg">支付宝手机网站支付</td>
                            <td>移动网页</td>
                            <td>
                                @if(settings('ibrand_alipay_app_id'))
                                    <span class="text-green">已开通</span>
                                @else
                                    <span class="text-gray">未开通</span>
                                @endif
                            </td>
                        </tr>
                        <tr onclick="location.href='{{route('admin.setting.editPayChannels',['type'=>'alipay_web'])}}'">
                            <td><img src="/assets/backend/images/alipay_icon.jpg">支付宝电脑网站支付</td>
                            <td>PC 端网页</td>
                            <td>
                                @if(settings('ibrand_alipay_app_id'))
                                    <span class="text-green">已开通</span>
                                @else
                                    <span class="text-gray">未开通</span>
                                @endif
                            </td>
                        </tr>
                        <tr onclick="location.href='{{route('admin.setting.editPayChannels',['type'=>'wechat'])}}'">
                            <td><img src="/assets/backend/images/wechat_pay_icon.jpg">微信公众号支付</td>
                            <td>服务号/扫码/企业付款</td>
                            <td>
                                @if(settings('ibrand_wechat_pay_app_id'))
                                    <span class="text-green">已开通</span>
                                @else
                                    <span class="text-gray">未开通</span>
                                @endif
                            </td>
                        </tr>
                        <tr onclick="location.href='{{route('admin.setting.editPayChannels',['type'=>'mini_program'])}}'">
                            <td><img src="/assets/backend/images/mini_pay_icon.jpg">微信小程序支付</td>
                            <td>微信小程序</td>
                            <td>
                                @if(settings('ibrand_miniapp_pay_miniapp_id'))
                                    <span class="text-green">已开通</span>
                                @else
                                    <span class="text-gray">未开通</span>
                                @endif
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function () {
                    swal("保存成功!", "", "success")
                }
            });
        });
    </script>