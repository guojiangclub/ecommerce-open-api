{{--@extends ('backend.layouts.default')--}}

{{--@section ('title','用户管理')--}}
{{----}}
{{--@section('page-header')--}}
    {{--<h1>--}}
        {{--用户管理--}}
        {{--<small>用户列表</small>--}}
    {{--</h1>--}}
{{--@endsection--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">{!! link_to_route('admin.users.index', '会员管理') !!}</li>--}}
    {{--</ol>--}}

{{--@stop--}}

{{--@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 会员列表</a></li>
            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">搜索</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    @include('backend.auth.includes.header-buttons')

                    @include('backend.auth.includes.user_list')

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
                            <form action="" method="get">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">会员名</label>
                                    <div class="col-sm-10"><input type="text" name="name"  class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">邮箱</label>
                                    <div class="col-sm-10"><input type="text" name="email"  class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">电话</label>
                                    <div class="col-sm-10"><input type="text" name="mobile"   class="form-control"></div>
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">积分</label>--}}
                                    {{--<div class="col-sm-10"><input type="text" name="integral"  class="form-control"></div>--}}
                                {{--</div>--}}

                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">搜索</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
{{--@stop--}}