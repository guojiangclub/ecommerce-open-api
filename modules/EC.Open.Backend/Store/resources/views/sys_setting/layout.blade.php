@extends('backend::layouts.default')

@section('sidebar-menu')

    <li class="{{ Active::pattern('admin/setting/pay') }}">
        <a href="{{route('admin.setting.pay')}}"><i class="iconfont icon-zhifushezhi"></i> <span
                    class="nav-label">支付设置</span></a>
    </li>

    <li class="{{ Active::pattern('admin/setting/theme') }}">
        <a href="{{route('admin.setting.theme')}}"><i class="iconfont icon-zhutishezhi"></i> <span class="nav-label">主题设置</span></a>
    </li>

    <li class="{{ Active::pattern('admin/setting/wechat*') }}">
        <a href="{{route('admin.setting.wechat')}}"><i class="iconfont icon-weixinguanli"></i> <span class="nav-label">模板消息设置</span></a>
    </li>

    @adminrole('administrator','manager')

    <li class="{{ Active::pattern('admin/setting/sms') }}">
        <a href="{{route('admin.setting.sms')}}"><i class="iconfont icon-duanxinshezhi"></i> <span class="nav-label">短信设置</span></a>
    </li>

    <li class="{{ Active::pattern('admin/setting/backend') }}">
        <a href="{{route('admin.setting.backend')}}"><i class="iconfont icon-houtaiguanli"></i> <span class="nav-label">后台设置</span></a>
    </li>

    <li class="{{ Active::pattern('admin/setting/analytics') }}">
        <a href="{{route('admin.setting.analytics')}}"><i class="iconfont icon-wangluotongjipeizhi"></i> <span
                    class="nav-label">网站统计配置</span></a>
    </li>

    <li class="{{ Active::pattern('admin/setting/sentry') }}">
        <a href="{{route('admin.setting.sentry')}}"><i class="iconfont icon-sentry"></i> <span
                    class="nav-label">Sentry</span></a>
    </li>

    @endadminrole


    @adminrole('administrator')

    <li class="{{ Active::pattern(['admin/access*']) }}">
        <a href="#"><i class="iconfont icon-guanliyuanguanli"></i>
            <span class="nav-label">管理员管理</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level collapse">
            <li class="{{ Active::pattern('admin/access/manager') }}">
                <a href="{{route('admin.access.manager')}}">管理员列表</a>
            </li>


            <li class="{{ Active::pattern('admin/access/role') }}">
                <a href="{{route('admin.access.role')}}">管理员角色</a>
            </li>

            <li class="{{ Active::pattern('admin/access/permission') }}">
                <a href="{{route('admin.access.permission')}}">管理员权限</a>
            </li>

            <li class="{{ Active::pattern('admin/access/manager/loginLog*') }}">
                <a href="{{route('admin.access.manager.loginLog')}}">登录日志</a>
            </li>

            {{--<li class="{{ Active::pattern('admin/access/databaseLog*') }}">
                <a href="{{route('admin.access.databaseLog.index')}}">操作日志</a>
            </li>--}}
        </ul>
    </li>



    <li class="{{ Active::pattern(['admin/log-viewer*','admin/debug*']) }}">
        <a href="#"><i class="iconfont icon-rizhiguanli"></i>
            <span class="nav-label">日志管理</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level collapse">
            <li class="{{ Active::pattern(['admin/log-viewer*']) }}"><a
                        href="{!! url('admin/log-viewer/logs') !!}">错误日志</a></li>
            <li class="{{ Active::pattern(['admin/debug*']) }}"><a href="{{route('admin.debug.jobs.index')}}">队列日志</a>
            </li>
            {{--<li><a href="{!! url('admin/log-viewer/logs') !!}">操作日志</a></li>--}}
        </ul>
    </li>


    <li class="{{ Active::pattern('admin/uploads') }}">
        <a href="{{route('admin.uploads.index')}}"><i class="iconfont icon-shangchuanrenzhengwenjian"></i> <span
                    class="nav-label">上传验证文件</span></a>
    </li>


    @endadminrole

@endsection