
<div class="tabs-container">
    @if (session()->has('flash_notification.message'))
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {!! session('flash_notification.message') !!}
        </div>
    @endif

    {{--<ul class="nav nav-tabs">--}}
        {{--<li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">微页面</a></li>--}}
        {{--<li><a href="{{route('admin.setting.micro.page.edit')}}">微页面编辑</a></li>--}}
    {{--</ul>--}}

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">

                <div class="row" style="margin-bottom:20px;">

                    <div class="col-sm-4">
                        <a onclick="create()" class="btn btn-w-m btn-info">创建微页面</a>
                    </div>

                    <div class="col-sm-4"></div>

                    <div class="col-sm-4">

                        <form action="{{route('admin.setting.micro.page.index')}}" method="get">

                            <div class="col-sm-10">
                                <div class="input-group search_text col-sm-12">
                                    <input type="text" name="name" placeholder="标题"
                                           value="{{!empty(request('name'))?request('name'):''}}" class="form-control">

                                </div>
                            </div>

                            <div class="col-sm-2">
                                <input class="btn btn-info" type="submit" value="搜索"/>
                            </div>

                        </form>

                    </div>



                </div>

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-sm-2">标题</th>
                        <th class="col-sm-2">推广链接</th>
                        <th class="col-sm-2">code</th>
                        <th class="col-sm-1">创建时间</th>
                        <th class="col-sm-2">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($lists->count())
                        @foreach($lists as $item)
                            <tr>

                                <td>

                                    <a  onclick="edit('{{$item->name}}','{{$item->id}}')"> {{$item->name}}</a>

                                    @if($item->page_type==2)
                                    <span  class="label label-info pull-right">首页</span>
                                    @endif

                                    @if($item->page_type==3)
                                        <span  class="label label-info pull-right">分类页</span>
                                    @endif

                                </td>

                                <td id="foo{{$item->id}}">
                                    {{$item->link}}
                                    @if($item->link)
                                    <a class="btn btn-xs btn-info pull-right" data-clipboard-action="copy"
                                       data-clipboard-target="#foo{{$item->id}}">复制</a>
                                        @endif
                                </td>
                                <td>{{$item->code}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    <a
                                            class="btn btn-xs btn-primary"
                                            href="{{route('admin.setting.micro.page.name.edit',$item->id)}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>


                                    <span  class="btn btn-xs btn-danger delete"  data-href="{{route('admin.setting.micro.page.delete',['id'=>$item->id])}}">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top"

                                                   title="删除"></i>
                                            </span>

                                    @if($item->page_type==1)

                                    <span  class="btn btn-xs btn-default index"  data-href="{{route('admin.setting.micro.page.setIndexPage',['id'=>$item->id])}}">
                                                <i class="fa" data-toggle="tooltip" data-placement="top"
                                                   title="设为首页">设为首页</i>
                                            </span>
                                    @endif

                                    @if($item->page_type==1)

                                    <span  class="btn btn-xs btn-default category"  data-href="{{route('admin.setting.micro.page.setCategoryPage',['id'=>$item->id])}}">
                                                <i class="fa" data-toggle="tooltip" data-placement="top"
                                                   title="设为分类页">设为分类页</i>
                                     </span>

                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                @if($lists->count())
                    <div class="pull-lift">
                        {!! $lists->appends(request()->except('page'))->render() !!}
                    </div>
                @endif

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<script src="//cdn.bootcss.com/clipboard.js/1.6.1/clipboard.min.js"></script>

<script>

    window.t='';

    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function (e) {
        swal("复制成功", "", "success")
    });

    clipboard.on('error', function (e) {

    });

    function create() {
        swal({
                title: "创建微页面",
                text: "",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "保存",
                cancelButtonText: "取消",
                animation: "slide-from-top",
                inputPlaceholder: "请输入标题"
            },
            function (inputValue) {
                if (inputValue == "") {
                    swal.showInputError("请输入标题");
                    return false
                }
                if(window.t==inputValue){
                    return false;
                }
                window.t=inputValue;

                var url = "{{route('admin.setting.micro.page.store',['_token'=>csrf_token()])}}";

                var data = {'name': inputValue}

                $.get(url, data, function (ret) {
                    if (!ret.status) {
                        swal("创建失败!", ret.message, "warning");
                    } else {
                        swal({
                            title: "创建成功",
                            text: "",
                            type: "success",
                            confirmButtonText: "确定"
                        }, function () {
                            location.href = "";
                        });
                    }
                });

            });
    }

    function edit(name,id) {
        window.microPageId=id;
        swal({
                title: "修改微页面标题",
                text: "",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "保存",
                cancelButtonText: "取消",
                animation: "slide-from-top",
                inputPlaceholder: "请输入标题",
                inputValue:name
            },
            function (inputValue,id) {
                if (inputValue == "") {
                    swal.showInputError("请输入标题");
                    return false
                }
                var url = "{{route('admin.setting.micro.page.name.update',['_token'=>csrf_token()])}}";

                var data = {'name': inputValue,'id':window.microPageId};

                $.post(url, data, function (ret) {
                    if (!ret.status) {
                        swal("保存失败!", ret.message, "warning");
                    } else {
                        swal({
                            title: "保存成功",
                            text: "",
                            type: "success",
                            confirmButtonText: "确定"
                        }, function () {
                            location.href = "";
                        });
                    }
                });

            });
    }


    $('.delete').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要删除吗？",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.setting.micro.page.index')}}';
                    });
                } else {
                    swal({
                        title: '删除失败',
                        text: result.message,
                        type: "error"
                    });
                }
            });
        });
    });

    $('.index').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要设置该微页面为首页么？",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.setting.micro.page.index')}}';
                    });
                } else {
                    swal({
                        title: '保存失败',
                        text: result.message,
                        type: "error"
                    });
                }
            });
        });
    });


    $('.category').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要设置该微页面为分类页么？",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.setting.micro.page.index')}}';
                    });
                } else {
                    swal({
                        title: '保存失败',
                        text: result.message,
                        type: "error"
                    });
                }
            });
        });
    });

</script>

