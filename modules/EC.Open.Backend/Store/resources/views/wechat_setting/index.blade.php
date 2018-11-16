{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h3>消息推送</h3>
    <ol class="breadcrumb" style="float: left">
        消息推送功能可以通过微信公众号，给买家或商家推送交易和物流相关的提醒消息，包括订单催付、发货、签收、退款等，以提升买家的购物体验，获得更高的订单转化率和复购率。
    </ol>
@endsection--}}
<style>
    .switches {
        margin-top: 10px;
    }

    ol, ul {
        list-style: none;
    }

    .switch-item {
        float: left;
        width: 20%;
        margin-bottom: 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        margin-right: 1.33%;
        background-color: #f8f8f8;
        padding: 10px 20px;
        position: relative;
    }

    a {
        color: #38f;
        text-decoration: none;
    }

    .switch-item .title {
        font-size: 18px;
        line-height: 1.5;
        margin-bottom: 10px;
        color: #38f;
    }

    .switch-item .disable {
        color: #f30;
    }

    .switch-item .disable .free-tip {
        color: #999;
    }

    .switch-item .switch-setting {
        display: none;
        color: #07d;
    }

    .pull-right {
        float: right;
    }

    .switch-item .enable {
        color: #4b0;
    }
</style>

{{--@section('content')--}}
    <div class="ibox-content" style="display: block;">
        <ul class="clearfix switches">
            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.order.remind')}}">
                    <h4 class="title">订单付款提醒</h4>
                    <p class="{{isset($order['status']) && $order['status']==1 ? 'enable' : 'disable'}}"> {{isset($order['status']) && $order['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.goods.deliver')}}">
                    <h4 class="title">发货通知</h4>
                    <p class="{{isset($deliver['status']) && $deliver['status']==1 ? 'enable' : 'disable'}}">{{isset($deliver['status']) && $deliver['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.goods.arrival')}}">
                    <h4 class="title">订阅到货提醒</h4>
                    <p class="{{isset($arrival['status']) && $arrival['status']==1 ? 'enable' : 'disable'}}">{{isset($arrival['status']) && $arrival['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.sales.service')}}">
                    <h4 class="title">售后服务处理进度提醒</h4>
                    <p class="{{isset($service['status']) && $service['status']==1 ? 'enable' : 'disable'}}">{{isset($service['status']) && $service['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.goods.refund')}}">
                    <h4 class="title">退货结果通知</h4>
                    <p class="{{isset($refund['status']) && $refund['status']==1 ? 'enable' : 'disable'}}">{{isset($refund['status']) && $refund['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.customer.paid')}}">
                    <h4 class="title">客户付款通知</h4>
                    <p class="{{isset($customer['status']) && $customer['status']==1 ? 'enable' : 'disable'}}">{{isset($customer['status']) && $customer['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.money.changed')}}">
                    <h4 class="title">帐户资金变动提醒</h4>
                    <p class="{{isset($money['status']) && $money['status']==1 ? 'enable' : 'disable'}}">{{isset($money['status']) && $money['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.point.changed')}}">
                    <h4 class="title">会员积分变动提醒</h4>
                    <p class="{{isset($point['status']) && $point['status']==1 ? 'enable' : 'disable'}}">{{isset($point['status']) && $point['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.charge.success')}}">
                    <h4 class="title">充值成功提醒</h4>
                    <p class="{{isset($charge['status']) && $charge['status']==1 ? 'enable' : 'disable'}}">{{isset($charge['status']) && $charge['status']==1 ? '已启用' : '未启用'}}
                        <span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.member.grade')}}">
                    <h4 class="title">会员等级变更通知</h4>
                    <p class="{{isset($member['status']) && $member['status']==1 ? 'enable' : 'disable'}}">{{isset($member['status']) && $member['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.sales.notice')}}">
                    <h4 class="title">订单售后通知</h4>
                    <p class="{{isset($notice['status']) && $notice['status']==1 ? 'enable' : 'disable'}}">{{isset($notice['status']) && $notice['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.groupon.success')}}">
                    <h4 class="title">拼团成功提醒</h4>
                    <p class="{{isset($grouponSuccess['status']) && $grouponSuccess['status']==1 ? 'enable' : 'disable'}}">
                        {{isset($grouponSuccess['status']) && $grouponSuccess['status']==1 ? '已启用' : '未启用'}}<span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.groupon.failed')}}">
                    <h4 class="title">拼团失败提醒</h4>
                    <p class="{{isset($grouponFailed['status']) && $grouponFailed['status']==1 ? 'enable' : 'disable'}}">
                        {{isset($grouponFailed['status']) && $grouponFailed['status']==1 ? '已启用' : '未启用'}}<span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.activity.notice')}}">
                    <h4 class="title">活动结果通知</h4>
                    <p class="{{isset($activity_notice['status']) && $activity_notice['status']==1 ? 'enable' : 'disable'}}">
                        {{isset($activity_notice['status']) && $activity_notice['status']==1 ? '已启用' : '未启用'}}<span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            <li class="switch-item">
                <a href="{{route('admin.setting.wechat.activity.notice.gift')}}">
                    <h4 class="title">礼品领取成功通知</h4>
                    <p class="{{isset($activity_notice_gift['status']) && $activity_notice_gift['status']==1 ? 'enable' : 'disable'}}">
                        {{isset($activity_notice_gift['status']) && $activity_notice_gift['status']==1 ? '已启用' : '未启用'}}<span></span>
                        <span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>

            {{--<li class="switch-item">
                <a href="{{route('admin.setting.wechat.refund.result')}}">
                    <h4 class="title">退货结果通知</h4>
                    <p class="{{isset($result['status']) && $result['status']==1 ? 'enable' : 'disable'}}">{{isset($result['status']) && $result['status']==1 ? '已启用' : '未启用'}}<span></span><span class="pull-right switch-setting">设置</span>
                    </p>
                </a>
            </li>--}}
        </ul>
    </div>
{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success")
                }
            });

            $('.switch-item').mouseover(function(){
                $(this).children('a').children('p').children('.switch-setting').show();
            }).mouseleave(function(){
                $(this).children('a').children('p').children('.switch-setting').hide();
            });
        })
    </script>
{{--@stop--}}