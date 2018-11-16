{{--@extends('store-backend::dashboard')--}}

{{--@section ('title','商城设置')--}}

{{--@section('breadcrumbs')--}}
{{--<h2>商城设置</h2>--}}
{{--<ol class="breadcrumb">--}}
{{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
{{--<li class="active">常用设置</li>--}}
{{--</ol>--}}
{{--@endsection--}}

{{--@section('after-styles-end')--}}
{{--<link href="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.css" rel="stylesheet">--}}
{{--@endsection--}}

{{--@section('content')--}}

<div class="tabs-container">
    {{--<form method="get" action="{{route('admin.setting.clearCache')}}" class="form-horizontal"--}}
    {{--id="setting_cache">--}}
    {{--{{csrf_field()}}--}}
    {{--<div class="form-group">--}}
    {{--<label class="col-sm-2 control-label">缓存：</label>--}}
    {{--<div class="col-sm-10">--}}
    {{--<button class="btn btn-warning" type="submit">清除缓存</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</form>--}}

    <ul class="nav nav-tabs">

        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">基础设置</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">高级设置</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">核心设置</a></li>
        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">其他设置</a></li>
    </ul>
    <form method="post" action="{{route('admin.setting.saveShopSetting')}}" class="form-horizontal"
          id="setting_site_form">
        {{csrf_field()}}
        <div class="tab-content">

            <div class="tab-pane active" id="tab_1">
                <div class="panel-body">
                    <div class="form-group"><label class="col-sm-2 control-label">微信二维码:</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="shop_wechat_qrcode"
                                   value="{{settings('shop_wechat_qrcode')?settings('shop_wechat_qrcode'):''}}">
                            <img class="shop_wechat_qrcode"
                                 src="{{settings('shop_wechat_qrcode')?settings('shop_wechat_qrcode'):''}}" alt=""
                                 style="max-width: 100px;">
                            <div id="wechatPicker">选择图片</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">手机商城二维码:</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="mobile_shop_qrcode"
                                   value="{{settings('mobile_shop_qrcode')?settings('mobile_shop_qrcode'):''}}">
                            <img class="mobile_shop_qrcode"
                                 src="{{settings('mobile_shop_qrcode')?settings('mobile_shop_qrcode'):''}}" alt=""
                                 style="max-width: 100px;">
                            <div id="mobileShopPicker">选择图片</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">商家logo:</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="shop_show_logo"
                                   value="{{settings('shop_show_logo')?settings('shop_show_logo'):''}}">
                            <img class="shop_show_logo"
                                 src="{{settings('shop_show_logo')?settings('shop_show_logo'):''}}" alt=""
                                 style="max-width: 100px;">
                            <div id="logoPicker">选择图片</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">分享网店名称:</label>
                        <div class="col-sm-10"><input type="text" name="share_shop_name"
                                                      placeholder="如：ibrand商城"
                                                      class="form-control"
                                                      value="{{settings('share_shop_name')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">客服电话:</label>
                        <div class="col-sm-10"><input type="text" name="online_service_phone"
                                                      placeholder="400-8888-8888"
                                                      class="form-control"
                                                      value="{{settings('online_service_phone')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">客服工作时间:</label>
                        <div class="col-sm-10"><input type="text" name="online_service_time"
                                                      placeholder="周一至周日：9:00 - 22:00" class="form-control"
                                                      value="{{settings('online_service_time')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">在线客服URL:</label>
                        <div class="col-sm-10"><input type="text" name="online_service_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('online_service_url')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">ICP备案号:</label>
                        <div class="col-sm-10"><input type="text" name="shop_icp_number" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('shop_icp_number')}}"></div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group"><label class="col-sm-2 control-label">小程序首页分享标题:</label>
                        <div class="col-sm-10"><input type="text" name="miniprogram-home-page-share-title"
                                                      placeholder=""
                                                      class="form-control"
                                                      value="{{settings('miniprogram-home-page-share-title')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">H5首页分享标题:</label>
                        <div class="col-sm-10"><input type="text" name="h5-home-page-share-title" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('h5-home-page-share-title')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">H5首页分享描述:</label>
                        <div class="col-sm-10"><input type="text" name="h5-home-page-share-desc" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('h5-home-page-share-desc')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">H5/小程序首页分享图片:</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="h5-home-page-share-logo"
                                   value="{{settings('h5-home-page-share-logo')?settings('h5-home-page-share-logo'):''}}">
                            <img class="h5-home-page-share-logo"
                                 src="{{settings('h5-home-page-share-logo')?settings('h5-home-page-share-logo'):''}}"
                                 alt=""
                                 style="max-width: 100px;">
                            <div id="h5logoPicker">选择图片</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    {{--<div class="form-group"><label class="col-sm-2 control-label">到货提醒模板消息id:</label>
                        <div class="col-sm-10"><input type="text" name="wx_message_template_id" placeholder="" class="form-control" value="{{settings('wx_message_template_id')}}"></div>
                    </div>--}}
                </div>

            </div>

            <div class="tab-pane" id="tab_2">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">前端是否显示销量：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="shop_show_sell_nums" {{settings('shop_show_sell_nums') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="shop_show_sell_nums" {{!settings('shop_show_sell_nums') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">前端是否显示库存：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="shop_show_store" {{settings('shop_show_store') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="shop_show_store" {{!settings('shop_show_store') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">评论是否需要审核：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="order_comment_audit" {{settings('order_comment_audit') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="order_comment_audit" {{!settings('order_comment_audit') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">多少分钟未付款自动关闭订单：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="text" class="form-control valid-num"
                                       value="{{settings('order_auto_cancel_time')?settings('order_auto_cancel_time'):1440}}"
                                       name="order_auto_cancel_time" placeholder="">
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">多少天未收货自动收货：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="text" class="form-control valid-num"
                                       value="{{settings('order_auto_receive_time')?settings('order_auto_receive_time'):14}}"
                                       name="order_auto_receive_time" placeholder="">
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">确认收货后N天关闭评价入口：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="text" class="form-control valid-num"
                                       value="{{settings('order_auto_complete_time')?settings('order_auto_complete_time'):7}}"
                                       name="order_auto_complete_time" placeholder="">
                            </label>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">微信卡领取开启</label>
                        <div class="col-sm-10">
                            <div class="i-checks">
                                <label><input type="radio"
                                              {{empty(settings('wechat_card_status'))?"checked":""}}  value="0"
                                              name="wechat_card_status"/> <i></i> 关闭</label>&nbsp;&nbsp;
                                <label> <input type="radio"
                                               {{!empty(settings('wechat_card_status'))?"checked":""}}  value="1"
                                               name="wechat_card_status"/> <i></i> 开启 </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">微信卡领取链接</label>
                        <div class="col-sm-10"><input type="text" name="wechat_card_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('wechat_card_url')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">微信卡激活页面</label>
                        <div class="col-sm-10"><input type="text" name="wechat_card_activity_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('wechat_card_activity_url')}}"></div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">是否自动上架</label>
                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio"
                                       {{empty(settings('store_auto_in_sale'))?"checked":""}}  value="0"
                                       name="store_auto_in_sale"/> <i></i> 关闭&nbsp;&nbsp;
                                <input type="radio"
                                       {{!empty(settings('store_auto_in_sale'))?"checked":""}}  value="1"
                                       name="store_auto_in_sale"/> <i></i> 开启
                            </label>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">是否自动下架</label>
                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio"
                                       {{empty(settings('store_auto_out_sale'))?"checked":""}}  value="0"
                                       name="store_auto_out_sale"/> <i></i> 关闭&nbsp;&nbsp;

                                <input type="radio"
                                       {{!empty(settings('store_auto_out_sale'))?"checked":""}}  value="1"
                                       name="store_auto_out_sale"/> <i></i> 开启
                            </label>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">商品图片CDN开启</label>
                        <div class="col-sm-10">
                            <div class="i-checks">
                                <label><input type="radio"
                                              {{empty(settings('store_img_cdn_status'))?"checked":""}}  value="0"
                                              name="store_img_cdn_status"/> <i></i> 关闭</label>&nbsp;&nbsp;
                                <label> <input type="radio"
                                               {{!empty(settings('store_img_cdn_status'))?"checked":""}}  value="1"
                                               name="store_img_cdn_status"/> <i></i> 开启 </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">商品图片CDN链接</label>
                        <div class="col-sm-10"><input type="text" name="store_img_cdn_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('store_img_cdn_url')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">被替换域名 <i
                                    class="fa fa-question-circle"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="请勿输入http://"></i></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">http://</span>
                                <input type="text" name="store_img_replace_url" placeholder=""
                                       class="form-control"
                                       value="{{settings('store_img_replace_url')}}">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">并发秒杀用户数: <i
                                    class="fa fa-question-circle"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="为0时不限制秒杀用户数"></i></label>
                        <div class="col-sm-10"><input type="text" name="seckill_max_online_user" placeholder=""
                                                      class="form-control seckill"
                                                      value="{{settings('seckill_max_online_user')?settings('seckill_max_online_user'):0}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">超过并发秒杀用户数秒杀下单成功概率%: <i
                                    class="fa fa-question-circle"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="配合并发秒杀用户数使用（1-99）"></i></label>
                        <div class="col-sm-10"><input type="text" name="seckill_max_online_user_pass_rate"
                                                      placeholder=""
                                                      class="form-control seckill"
                                                      value="{{settings('seckill_max_online_user_pass_rate')?settings('seckill_max_online_user_pass_rate'):1}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_3">
                <div class="panel-body">
                    {{--<div class="form-group">
                        <label class="col-sm-2 control-label">颜色规格色系：</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>色系名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                <tbody id="colorBody">
                                @if($color = settings('goods_spec_color'))
                                    @foreach ($color as $key => $value)

                                        <tr class="colorList">
                                            <td>
                                                <input readonly type="text" name="goods_spec_color[{{$key}}]"
                                                       class="form-control" value="{{$value}}">
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-danger del-color" href="javascript:;"
                                                   onclick="delColor(this)">
                                                    <i data-toggle="tooltip" data-placement="top"
                                                       class="fa fa-trash"
                                                       title="删除"></i></a>

                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button type="button" id="add-color" class="btn btn-w-m btn-info">添加色系
                                        </button>
                                    </td>
                                </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>--}}


                    <div class="form-group"><label class="col-sm-2 control-label">PC端域名:</label>
                        <div class="col-sm-10"><input type="text" name="pc_store_domain_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('pc_store_domain_url')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">移动端域名:</label>
                        <div class="col-sm-10"><input type="text" name="mobile_domain_url" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('mobile_domain_url')}}"></div>
                    </div>


                    <div class="hr-line-dashed"></div>

                    <div class="form-group"><label class="col-sm-2 control-label">PC微信登录client_id:</label>
                        <div class="col-sm-10"><input type="text" name="wechat_login_client_id" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('wechat_login_client_id')}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">PC微信登录client_secret:</label>
                        <div class="col-sm-10"><input type="text" name="wechat_login_client_secret" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('wechat_login_client_secret')}}"></div>
                    </div>

                    {{--<div class="form-group"><label class="col-sm-2 control-label">数据分析site_id</label>
                     <div class="col-sm-10"><input type="text" name="analytics_site_id" placeholder=""
                                                   class="form-control"
                                                   value="{{settings('analytics_site_id')}}"></div>
                 </div>--}}
                </div>

            </div>
            <div class="tab-pane" id="tab_4">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">发布游记是否审核：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="travel_content_publish_audit" {{settings('travel_content_publish_audit') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="travel_content_publish_audit" {{!settings('travel_content_publish_audit') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">发布游记获得积分：</label>

                        <div class="col-sm-10"><input type="text" name="travel_content_publish_point_value" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('travel_content_publish_point_value')?settings('travel_content_publish_point_value'):3}}"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">点赞游记获得积分：</label>

                        <div class="col-sm-10"><input type="text" name="travel_content_like_point_value" placeholder=""
                                                      class="form-control"
                                                      value="{{settings('travel_content_like_point_value')?settings('travel_content_like_point_value'):1}}"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否启用打卡签到(小程序)：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_need_sign_in" {{settings('other_need_sign_in') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_need_sign_in" {{!settings('other_need_sign_in') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否启用积分商城：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_point_mall" {{settings('other_point_mall') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_point_mall" {{!settings('other_point_mall') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">获取微信群信息(小程序)：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_get_gid" {{settings('other_get_gid') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_get_gid" {{!settings('other_get_gid') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">开启门店自提(小程序)：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_pick_self" {{settings('other_pick_self') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_pick_self" {{!settings('other_pick_self') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">未绑定手机号是否可下单：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_disable_mobile" {{settings('other_disable_mobile') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_disable_mobile" {{!settings('other_disable_mobile') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">分类页面样式(小程序)：</label>
                        <div class="col-sm-10">
                            <div class="i-checks">
                                <label><input type="radio"
                                              {{(settings('mini_category_list_type')=='upper_lower' OR empty(settings('mini_category_list_type')))?"checked":""}}  value="upper_lower"
                                              name="mini_category_list_type"/> <i></i> 上下结构</label>&nbsp;&nbsp;
                                <a href="{{url('assets/backend/images/category_upper_lower.jpg')}}"
                                   target="_blank">查看样例</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <label> <input type="radio"
                                               {{settings('mini_category_list_type')=='left_right'?"checked":""}}  value="left_right"
                                               name="mini_category_list_type"/> <i></i> 左右结构 </label>&nbsp;&nbsp;
                                <a href="{{url('assets/backend/images/category_left_right.jpg')}}"
                                   target="_blank">查看样例</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否启用圈子功能(小程序)：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_built_sns" {{settings('other_built_sns') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_built_sns" {{!settings('other_built_sns') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="other_built_sns_box"
                         style="display: {{settings('other_built_sns')?'':'none'}}">
                        <label class="col-sm-2 control-label">圈子名称：</label>

                        <div class="col-sm-10">
                            <input type="text" name="other_built_sns_title"
                                   placeholder="米粉圈"
                                   class="form-control"
                                   value="{{settings('other_built_sns_title')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">页面底部是否显示技术支持：</label>

                        <div class="col-sm-10">
                            <label class="control-label">
                                <input type="radio" value="1"
                                       name="other_technical_support" {{settings('other_technical_support') ? 'checked': ''}}>
                                是
                                &nbsp;&nbsp;
                                <input type="radio" value="0"
                                       name="other_technical_support" {{!settings('other_technical_support') ? 'checked': ''}}>
                                否
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-group" style=" margin-top: 15px">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">保存设置</button>
            </div>
        </div>
    </form>
</div>


<script id="color-template" type="text/x-template">
    <tr class="colorList">
        <td>
            <input type="text" name="goods_spec_color[{NUM}]" class="form-control" placeholder="色系名称">
        </td>
        <td>
            <a class="btn btn-xs btn-danger del-color" href="javascript:;" onclick="delColor(this)">
                <i data-toggle="tooltip" data-placement="top"
                   class="fa fa-trash"
                   title="删除"></i></a>
        </td>
    </tr>
</script>
{{--@endsection

@section('after-scripts-end')--}}

{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
--}}
<script>
    $('.valid-num').bind('input propertychange', function (e) {
        var value = $(e.target).val()
        if (!/^[-]?[0-9]*\.?[0-9]+(eE?[0-9]+)?$/.test(value)) {
            value = value.replace(/[^\d.].*$/, '');
            $(e.target).val(value);
        } else if (value.indexOf('-') != -1) {
            $(e.target).val('');
        }
    });


    function delColor(_self) {
        $(_self).parent().parent().remove();
    }

    $("input[name='other_built_sns']").on('ifClicked', function () {
        console.log('aa');
        var point_setting_box = $('#other_built_sns_box');
        if ($(this).val() == 1) {
            point_setting_box.show();
        } else {
            point_setting_box.hide();
        }
    });

    $(function () {
        var color_html = $('#color-template').html();
        $('#add-color').click(function () {
            var num = $('.colorList').length;
            $('#colorBody').append(color_html.replace(/{NUM}/g, num));
        });

        var uploader = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#wechatPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file, response) {
            var img_url = response.url;
            $('input[name="shop_wechat_qrcode"]').val(AppUrl + img_url);
            $('.shop_wechat_qrcode').attr('src', img_url);
        });

        var mobileShopPicker = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#mobileShopPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        mobileShopPicker.on('uploadSuccess', function (file, response) {
            var img_url = response.url;
            $('input[name="mobile_shop_qrcode"]').val(img_url);
            $('.mobile_shop_qrcode').attr('src', img_url);
        });

        //logo
        var logoPicker = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#logoPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        logoPicker.on('uploadSuccess', function (file, response) {
            var img_url = response.url;

            $('input[name="shop_show_logo"]').val(img_url);
            $('.shop_show_logo').attr('src', img_url);
        });

        //h5 logo
        var h5logoPicker = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#h5logoPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        h5logoPicker.on('uploadSuccess', function (file, response) {
            var img_url = response.url;

            $('input[name="h5-home-page-share-logo"]').val(img_url);
            $('.h5-home-page-share-logo').attr('src', img_url);
        });


        /* $('#setting_site_form').find("input[type='radio']").iCheck({
         checkboxClass: 'icheckbox_square-green',
         radioClass: 'iradio_square-green',
         increaseArea: '20%'
         });*/


        $('.seckill').bind('input propertychange', function (e) {
            var value = $(e.target).val()
            if (!/^[-]?[0-9]*\.?[0-9]+(eE?[0-9]+)?$/.test(value)) {
                value = value.replace(/[^\d].*$/, '');
                $(e.target).val(value);
            }
        });

        $('#setting_site_form').ajaxForm({
            success: function (result) {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location.reload();
                });

            }
        });

        $('#setting_cache').ajaxForm({
            success: function (result) {
                swal({
                    title: "执行成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location.reload();
                });

            }
        });
    })
</script>
{{--@stop--}}