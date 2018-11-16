@extends('backend::layouts.default')

@section('sidebar-menu')
    {{--<li class="{{ Active::pattern('admin/users*') }}">
        <a href="#"><i class="fa fa-user"></i>
            <span class="nav-label">会员管理</span>
            <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li class="{{ Active::pattern('admin/users/') }}"><a
                        href="{{route('admin.users.index')}}"> 会员管理</a></li>
            <li class="{{ Active::pattern('admin/users/group*') }}"><a
                        href="{{route('admin.users.grouplist')}}">会员等级管理</a></li>

        </ul>
    </li>--}}
    {{--<li class="{{ Active::pattern('admin/manager*') }}">
        <a href="{{route('admin.manager.index')}}"><i
                    class="fa fa-user-plus"></i> <span class="nav-label">管理员管理</span></a>
    </li>--}}
    <li class="{{ Active::pattern('admin/member/users*') }}">
        <a href="{{route('admin.users.index')}}"><i
                    class="iconfont icon-huiyuanguanli--"></i> <span class="nav-label">会员管理</span></a>
    </li>
    <li class=" {{ Active::pattern('admin/member/group*') }}">
        <a href="{{route('admin.users.group.list')}}"><i
                    class="iconfont icon-huiyuandengjiguanli"></i> <span class="nav-label">会员分组管理</span></a>
    </li>
    <li class="{{ Active::pattern('admin/member/card*') }}">
        <a href="{{route('admin.member.card')}}">
            <i class="iconfont icon-dingdanguanli"></i>
            <span class="nav-label">会员卡</span>
        </a>
    </li>
    <li class=" {{ Active::pattern('admin/member/groups*') }}">
        <a href="{{route('admin.users.grouplist')}}"><i
                    class="iconfont icon-huiyuandengjiguanli"></i> <span class="nav-label">会员等级管理</span></a>
    </li>

    <li class=" {{ Active::pattern('admin/member/point*') }}">
        <a href="{{route('admin.users.pointlist')}}"><i
                    class="iconfont icon-huiyuanjifenjilu"></i> <span class="nav-label">会员积分记录</span></a>
    </li>

    <li class=" {{ Active::pattern('admin/member/balances*') }}">
        <a href="{{route('admin.users.balances.list')}}"><i
                    class="iconfont icon-huiyuanjifenjilu"></i> <span class="nav-label">会员余额记录</span></a>
    </li>


    <li class=" {{ Active::pattern('admin/member/recharge*') }}">
        <a href="{{route('admin.users.recharge.index')}}"><i
                    class="iconfont icon-huiyuanjifenjilu"></i> <span class="nav-label">储值管理</span></a>
    </li>

    <li class=" {{ Active::pattern('admin/member/log_recharge') }}">
        <a href="{{route('admin.users.recharge.log.index')}}"><i
                    class="iconfont icon-huiyuanjifenjilu"></i> <span class="nav-label">储值记录</span></a>
    </li>

    @adminrole('administrator')
    <li class=" {{ Active::pattern('admin/member/staff*') }}">
        <a href="{{route('admin.staff.index')}}"><i
                    class="iconfont icon-yuangongguanli"></i> <span class="nav-label">员工管理</span></a>
    </li>
    @endadminrole


    {{--<li class=" {{ Active::pattern('admin/member/recharge/log*') }}">--}}
        {{--<a href="{{route('admin.users.recharge.index')}}"><i--}}
                    {{--class="iconfont icon-huiyuanjifenjilu"></i> <span class="nav-label">储值记录</span></a>--}}
    {{--</li>--}}


    <li class="{{ Active::pattern('admin/member/RoleManagement/*') }}">
        <a href="{{route('admin.RoleManagement.role.index')}}"><i class="iconfont icon-jiaoseguanli"></i> <span
                    class="nav-label">角色管理</span></a>
    </li>




    {{--<li class="{{ Active::pattern('admin/RoleManagement/role*') }}">--}}
    {{--<a href="{{route('admin.RoleManagement.permission.index')}}"><i class="fa fa-gears"></i> <span class="nav-label">权限管理</span></a>--}}
    {{--</li>--}}

    @if(env('CUSTOMIZATION'))
        {{--<li class=" {{ Active::pattern('admin/statistics*') }}">--}}
        {{--<a href="{{route('admin.statistics.index')}}"><i--}}
        {{--class="fa fa-diamond"></i> <span class="nav-label">数据统计</span></a>--}}
        {{--</li>--}}

        <li class=" {{ Active::pattern('admin/card*') }}">
            <a href="{{route('admin.card.index')}}"><i
                        class="iconfont icon-huiyuanqiaguanli"></i> <span class="nav-label">会员卡管理</span></a>
        </li>
    @endif

    @if(env('FUNTASY_CUSTOMIZATION'))

        <li class=" {{ Active::pattern('admin/card*') }}">
            <a href="{{route('admin.users.entity.list')}}"><i
                        class="iconfont icon-huiyuanqiaguanli"></i> <span class="nav-label">实体卡申请管理</span></a>
        </li>
    @endif

    {{--<li class=" {{ Active::pattern('admin/member/message*') }}">--}}
        {{--<a href="{{route('admin.users.message.index')}}"><i--}}
                    {{--class="iconfont icon-huiyuanqiaguanli"></i> <span class="nav-label">消息管理</span></a>--}}
    {{--</li>--}}

@endsection
