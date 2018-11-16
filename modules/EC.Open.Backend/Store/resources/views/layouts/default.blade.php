@extends('backend::layouts.default')

@section('sidebar-menu')

    @if(!session('admin_check_supplier'))
        <li class="{{ Active::pattern('admin/setting*') }}">
            <a href="#"><i class="iconfont icon-shangchengshezhi-"></i>
                <span class="nav-label">商城设置</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                @if(config('store.produce'))
                    <li class="{{ Active::pattern('admin/setting/produce') }}"><a
                                href="{{route('admin.setting.produce')}}">产品生产状态设置</a></li>
                @endif

                <li class="{{ Active::pattern('admin/setting/shopSetting') }}"><a
                            href="{{route('admin.setting.shopSetting')}}">商城设置</a></li>

                <li class="{{ Active::pattern('admin/setting/point') }}"><a
                            href="{{route('admin.setting.point')}}">积分设置</a></li>


                <li class="{{ Active::pattern('admin/setting/price/protection') }}"><a
                            href="{{route('admin.setting.price.protection')}}">价格保护</a></li>

                <li class="{{ Active::pattern('admin/setting/employee') }}"><a
                            href="{{route('admin.setting.employee')}}">员工内购</a></li>

                <li class="{{ Active::pattern('admin/setting/refund-reason') }}"><a
                            href="{{route('admin.setting.refund.reason')}}">售后设置</a></li>

                <li class="{{ Active::pattern('admin/setting/invoice') }}"><a
                            href="{{route('admin.setting.invoice')}}">发票设置</a></li>

                {{--<li class="{{ Active::pattern('admin/setting/siteSettings') }}"><a--}}
                {{--href="{{route('admin.setting.siteSettings')}}">站点配置</a></li>--}}

                <li class="{{ Active::pattern('admin/setting/tool') }}"><a
                            href="{{route('admin.setting.tool')}}">工具管理</a></li>

                    <li class="{{ Active::pattern('admin/setting/onlineService') }}"><a
                                href="{{route('admin.setting.onlineService')}}">客服设置</a></li>

            </ul>
        </li>

        <li class="{{ Active::pattern('admin/store*') }}">
            <a href="#"><i class="iconfont icon-shangpinshezhi-"></i>
                <span class="nav-label">商品管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/store/models*') }}"><a
                            href="{{route('admin.goods.model.index')}}">模型管理</a></li>
                <li class="{{ Active::pattern('admin/store/specs*') }}"><a
                            href="{{route('admin.goods.spec.index')}}"> 规格管理</a></li>
                <li class="{{ Active::pattern('admin/store/attribute*') }}"><a
                            href="{{route('admin.goods.attribute.index')}}"> 参数管理</a></li>
                <li class="{{ Active::pattern('admin/store/brand*') }}"><a
                            href="{{route('brand.index')}}"> 品牌管理</a></li>
                <li class="{{ Active::pattern(['admin/store/category_group*','admin/store/category*']) }}"><a
                            href="{{route('admin.cagetory.group.index')}}"> 分类管理</a></li>
                <li class="{{ Active::pattern('admin/store/goods*') }}"><a
                            href="{{route('admin.goods.index')}}"> 商品列表</a></li>
                <li class="{{ Active::pattern('admin/store/limit*') }}"><a
                            href="{{route('admin.store.goods.limit', ['status'=>'ACTIVITY'])}}"> 商品限购</a></li>
                <li class="{{ Active::pattern('admin/store/registrations*') }}"><a
                            href="{{route('registrations.index')}}"> 商品注册码</a></li>
                <li class="{{ Active::pattern('admin/store/cart') }}"><a
                            href="{{route('admin.cart.index')}}"> 购物车列表</a></li>
            </ul>
        </li>

        <li class="{{ Active::pattern('admin/promotion*') }}">
            <a href="#"><i class="iconfont icon-cuxiaoguanli"></i>
                <span class="nav-label">促销管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">

            <li class="{{ Active::pattern('admin/promotion/discount*') }}"><a
                        href="{{route('admin.promotion.discount.index')}}">促销活动管理</a></li>
            <li class="{{ Active::pattern('admin/promotion/coupon*') }}"><a
                        href="{{route('admin.promotion.coupon.index')}}">优惠券管理</a></li>
            <li class="{{ Active::pattern('admin/promotion/single-discount*') }}"><a
                        href="{{route('admin.promotion.singleDiscount.index')}}"> 单品折扣管理</a></li>
            <li class="{{ Active::pattern('admin/promotion/suit*') }}"><a
                        href="{{route('admin.promotion.suit.index')}}"> 套餐管理</a></li>
            <li class="{{ Active::pattern('admin/promotion/seckill*') }}"><a
                        href="{{route('admin.promotion.seckill.index')}}"> 秒杀管理</a></li>
            <li class="{{ Active::pattern('admin/promotion/groupon*') }}"><a
                        href="{{route('admin.promotion.groupon.index')}}">拼团管理</a></li>
            {{--<li class="{{ Active::pattern('admin/promotion/bundle/create') }}"><a--}}
                        {{--href="{{route('admin.promotion.bundle.create')}}"> 添加套餐</a></li>--}}


                <li class="{{ Active::pattern('admin/promotion/gift/new_user*') }}"><a
                            href="{{route('admin.promotion.gift.new.user.index')}}">新人进店礼</a></li>

                <li class="{{ Active::pattern('admin/promotion/directional/coupon*') }}"><a
                            href="{{route('admin.promotion.directional.coupon.index')}}">定向发券</a></li>


                <li class="{{ Active::pattern('admin/promotion/gift/birthday*') }}"><a
                            href="{{route('admin.promotion.gift.birthday.index')}}">生日礼</a></li>


            </ul>
        </li>

        <li class="{{ Active::pattern('admin/marketing*') }}">
            <a href="#"><i class="iconfont icon-shijianyingxiaoguanli"></i>
                <span class="nav-label">事件营销管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">

                <li class="{{ Active::pattern('admin/marketing/GOODS_REGISTER/list')}}"><a
                            href="{{route('admin.marketing.index',['type'=>'GOODS_REGISTER'])}}">商品注册事件管理</a></li>

                {{--<li class="{{ Active::pattern('admin/marketing/USER_REGISTER/list')}}"><a
                            href="{{route('admin.marketing.index',['type'=>'USER_REGISTER'])}}">用户注册事件管理</a></li>

                <li class="{{ Active::pattern('admin/marketing/INVITATION_REGISTER/list')}}"><a
                            href="{{route('admin.marketing.index',['type'=>'INVITATION_REGISTER'])}}">邀请注册事件管理</a></li>--}}

            </ul>
        </li>
    @endif

    <li class="{{ Active::pattern(['admin/order*','admin/refund*','admin/comments*','admin/offOrders']) }}">
        <a href="#"><i class="iconfont icon-dingdanguanli"></i>
            <span class="nav-label">订单管理</span>
            <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">

            <li class="{{ Active::pattern('admin/order*') }}"><a
                        href="{{route('admin.orders.index',['status' => 'all'])}}">订单列表</a></li>

            @if(!session('admin_check_supplier'))
                @if(env('CUSTOMIZATION'))
                    <li class="{{ Active::pattern('admin/offOrders') }}"><a
                                href="{{route('admin.orders.offOrders')}}">线下订单数据列表 </a></li>
                @endif

                <li class="{{ Active::pattern('admin/refund*') }}"><a
                            href="{{route('admin.refund.index')}}">售后管理</a></li>

                <li class="{{ Active::pattern('admin/comments*') }}"><a
                            href="{{route('admin.comments.index', ['status' => 'show'])}}">评论管理</a></li>

            @endif
        </ul>
    </li>

    @if(!session('admin_check_supplier'))
        <li class="{{ Active::pattern('admin/point-mall*') }}">
            <a href="#"><i class="iconfont icon-shangpinshezhi-"></i>
                <span class="nav-label">积分商城</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/point-mall/goods*') }}"><a
                            href="{{route('admin.point-mall.goods.index')}}">商品管理</a></li>
                <li class="{{ Active::pattern('admin/point-mall/orders*') }}"><a
                            href="{{route('admin.point-mall.orders.index',['status' => 'all'])}}">订单管理</a></li>

            </ul>
        </li>

        <li class="{{ Active::pattern(['admin/shippingmethod*','admin/shipping/template*'])}}">
            <a href="#"><i class="iconfont icon-wuliuguanli"></i>
                <span class="nav-label">物流管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">

                <li class="{{ Active::pattern('admin/shippingmethod/*') }}"><a
                            href="{{route('admin.shippingmethod.company')}}">物流列表</a></li>

                {{--<li class="{{ Active::pattern('admin/shipping/template*') }}"><a--}}
                {{--href="{{route('admin.shipping.template.index')}}">运费模板</a></li>--}}

            </ul>
        </li>

        @if(View::exists('file-manage::layouts.default'))
            @include('file-manage::layouts.default')
        @endif

        @if(View::exists('free-event::layouts.default'))
            @include('free-event::layouts.default')
        @endif

        <li class="{{ Active::pattern('admin/supplier*') }}">
            <a href="#"><i class="iconfont  icon-erpguanli"></i>
                <span class="nav-label">供应商管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/supplier') }}"><a
                            href="{{route('admin.supplier.index')}}">供应商列表</a></li>
                <li class="{{ Active::pattern(['admin/supplier/create','admin/supplier/edit*']) }}"><a
                            href="{{route('admin.supplier.create')}}">添加供应商</a></li>
            </ul>
        </li>
    @endif

    @if(env('CUSTOMIZATION'))

        <li class="{{ Active::pattern('admin/erp*') }}">
            <a href="#"><i class="iconfont  icon-erpguanli"></i>
                <span class="nav-label">ERP管理</span>
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/erp/all-orders') }}"><a
                            href="{{route('admin.erp.order.all',['status'=>2])}}">ERP 手动推单</a></li>
                <li class="{{ Active::pattern('admin/erp/order') }}"><a
                            href="{{route('admin.erp.order')}}">失败订单处理</a></li>
                <li class="{{ Active::pattern('admin/erp/log') }}"><a
                            href="{{route('admin.erp.log')}}">ERP日志列表</a></li>
            </ul>
        </li>
        <li class="{{ Active::pattern('admin/dataimport*') }} ">
            <a href="#"><i class="iconfont icon-houtaiguanli"></i>
                <span class="nav-label">数据导入</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/dataimport/createOrder') }}"><a
                            href="{{route('admin.dataimport.createOrder')}}">线下订单导入</a></li>
                <li class="{{ Active::pattern('admin/dataimport/createVipeak') }}"><a
                            href="{{route('admin.dataimport.createVipeak')}}">线下会员卡导入</a></li>
                <li class="{{ Active::pattern('admin/dataimport/createCoupon') }}"><a
                            href="{{route('admin.dataimport.createCoupon')}}">线下优惠券导入</a></li>
                <li class="{{ Active::pattern('admin/dataimport/createWebCouponCode') }}"><a
                            href="{{route('admin.dataimport.createWebCouponCode')}}">官网优惠码导入</a></li>
            </ul>
        </li>

        <li class="{{ Active::pattern('admin/wechat*') }} ">
            <a href="#"><i class="iconfont icon-houtaiguanli"></i>
                <span class="nav-label">微信卡券管理</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level collapse">
                <li class="{{ Active::pattern('admin/wechat/card*') }}"><a
                            href="{{route('admin.wechat.card.index')}}">卡券列表</a></li>
                <li class="{{ Active::pattern('admin/wechat/landingPage*') }}"><a
                            href="{{route('admin.wechat.landingPage.index')}}">货架列表</a></li>
                <li class="{{ Active::pattern('admin/wechat/mate*') }}"><a
                            href="{{route('admin.wechat.mate.index')}}">素材列表</a></li>

            </ul>
        </li>
    @endif

@endsection