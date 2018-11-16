    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">物流公司</a></li>
            <a no-pjax href="{{route('admin.shippingmethod.CompanyCreate')}}" class="btn btn-w-m btn-info pull-right">添加物流公司</a>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <a href="/assets/template/shipping_code.doc" style="margin: 10px 0;display: block;"
                       target="_blank">物流公司代码文件下载</a>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>物流公司名称</th>
                            <th>url网址</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($company_list as $company)
                            <tr>

                                <td>{!! $company->name !!}</td>
                                <td>{!! $company->url !!}</td>
                                <td>{{$company->is_enabled ? '使用中' : '已禁用'}}</td>

                                <td>
                                    <a no-pjax class="btn btn-xs btn-primary"
                                        href="{{route('admin.shippingmethod.CompanyCreate',['id'=>$company->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    <a class="btn btn-xs btn-danger delete"
                                       data-href="{{route('admin.shippingmethod.deletedCompany',['id'=>$company->id,'_token'=>csrf_token()])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

<script>
    $('.delete').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要删除吗?",
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
                        location = '{{route('admin.shippingmethod.company')}}';
                    });
                } else {
                    swal({
                        title: result.message,
                        text: "",
                        type: "warning"
                    });
                }
            });
        });
    });
</script>