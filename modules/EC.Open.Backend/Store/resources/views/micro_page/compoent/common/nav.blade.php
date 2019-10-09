<ul class="nav nav-tabs">
    <li @if($type=='micro_page_componet_slide')class="active"@endif >
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_slide')}}">幻灯片</a>
    </li>
    <li @if($type=='micro_page_componet_coupon')class="active"@endif>
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_coupon')}}">优惠券</a>
    </li>
    <li @if($type=='micro_page_componet_nav')class="active"@endif>
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_nav')}}">快捷导航</a>
    </li>
    <li @if($type=='micro_page_componet_cube')class="active"@endif>
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_cube')}}">魔方</a>
    </li>
    <li @if($type=='micro_page_componet_category')class="active"@endif>
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_category')}}">分类商品</a>
    </li>
    <li @if($type=='micro_page_componet_goods_group')class="active"@endif>
        <a href="{{route('admin.setting.micro.page.compoent.index','micro_page_componet_goods_group')}}">商品分组</a>
    </li>
</ul>