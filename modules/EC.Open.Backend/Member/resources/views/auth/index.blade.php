    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif
        <ul class="nav nav-tabs">
            <li class="{{ Active::pattern('admin/member/users') }}"><a data-toggle="tab" href="#tab-1"
                                                                       aria-expanded="true"> 会员列表</a></li>
            <li class="{{ Active::pattern('admin/member/users/banned') }}"><a href="{{route('admin.users.banned')}}">禁用的会员</a>
            </li>

            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">搜索</a></li>

            <a href="{{route('admin.users.create')}}" class="btn btn-w-m btn-info pull-right" style="margin-right: 5px">添加会员</a>


            <div class="btn-group pull-right" style="margin-right: 5px">
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                   href="javascript:;" data-style="zoom-in">导出<span
                            class="caret"></span></a>
                <ul class="dropdown-menu">

                    <li><a class="export-goods" data-toggle="modal"
                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.users.userexport',['type'=>'xls'])}}"
                           href="javascript:;">导出xls格式</a></li>

                    <li><a class="export-goods" data-toggle="modal"
                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.users.userexport',['type'=>'csv'])}}"
                           href="javascript:;">导出csv格式</a></li>
                </ul>
            </div>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    @include('member-backend::auth.includes.user-list')

                    <div class="pull-left">
                        {!! $users->total() !!} 个会员
                    </div>

                    <div class="pull-right">
                        {!! $users->render() !!}
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            @include('member-backend::auth.includes.search-user')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
    <div id="download_modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>

