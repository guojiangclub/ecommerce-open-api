{{--@extends ('member-backend::layout')--}}

{{--@section('title','消息管理')--}}

{{--@section('breadcrumbs')--}}
    {{--<h2>消息管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.RoleManagement.role.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
        {{--<li class="active">系统消息管理</li>--}}
    {{--</ol>--}}
    {{--@endsection--}}

    {{--@section('after-styles-end')--}}
            <!-- 引入样式 -->
    {!! Html::style(env("APP_URL").'/assets/backend/libs/element/index.css') !!}
{{--@stop--}}


{{--@section('content')--}}

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">已发系统消息</a></li>
            <a href="{!!route('admin.users.message.create')!!}" class="btn btn-w-m btn-info pull-right">发送消息</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="60%">消息内容</th>
                            <th>发送时间</th>
                            <th>接收用户</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach ($models as $model)--}}
                        <tr>
                            <td>I need add an Event Handler to the li element and then console.log() the name of the shirt they selected. I am getting a typeError: Cannot convert object to primitive value. I am new to this and am ...</td>
                            <td>2018-05-02 10:21</td>
                            <td>所有用户</td>
                            <td>
                                <a class="btn btn-xs btn-primary"
                                   href="#"> <i
                                            data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o"
                                            title="" data-original-title="编辑"></i></a>


                            </td>

                        </tr>
                        <tr>
                            <td>I already made an image that slides up when scrolls but when I go back to the top of the image then scroll down again it is already shown and not sliding up ...</td>
                            <td>2018-05-01 11:21</td>
                            <td>指定角色：供应商</td>
                            <td>
                                <a class="btn btn-xs btn-primary"
                                   href="#"> <i
                                            data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o"
                                            title="" data-original-title="编辑"></i></a>


                            </td>

                        </tr>
                        <tr>
                            <td>Sometimes, when the app crash, there is an option to see the crash log for the app and send to the developer. How do I do this in flutter app ...</td>
                            <td>2018-05-01 11:21</td>
                            <td>指定会员分组：特别会员</td>
                            <td>
                                <a class="btn btn-xs btn-primary"
                                   href="#"> <i
                                            data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o"
                                            title="" data-original-title="编辑"></i></a>


                            </td>

                        </tr>
                        <tr>
                            <td>I have a requirement to implement a search engine. Like google / yahoo search, In a search component if any key entered it should find the matches and displayed. For this requirement i have created ...</td>
                            <td>2018-05-01 11:21</td>
                            <td>指定用户：demo@demo.com</td>
                            <td>
                                <a class="btn btn-xs btn-primary"
                                   href="#"> <i
                                            data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o"
                                            title="" data-original-title="编辑"></i></a>


                            </td>

                        </tr>
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{--                        {!! $users->render() !!}--}}
                    </div>
                </div>
            </div>

        </div>
    </div>

{{--@endsection--}}

{{--@section('before-scripts-end')--}}

{{--@stop--}}