@if(Session::has('message'))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> 提示！</h4>
        {{ Session::get('message') }}
    </div>
@endif

<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <a href="{{ route('admin.goods.spec.create') }}" class="btn btn-primary margin-bottom" no-pjax>新建规格</a>

        <div>

            <div class="box-body table-responsive">
                <table class="table table-hover table-bordered">
                    <tbody>
                    <!--tr-th start-->
                    <tr>
                        <th>系统名称</th>
                        <th>显示名称</th>
                        <th>操作</th>
                    </tr>
                    <!--tr-th end-->

                    @foreach ($specs as $item)
                        <tr>
                            <td>{{$item->display_name}}</td>
                            <td>{{$item->name}}</td>
                            <td>
                                <a href="{{route('admin.goods.spec.edit',['id'=>$item->id])}}"
                                   class="btn btn-xs btn-primary" no-pjax><i class="fa fa-pencil" data-toggle="tooltip"
                                                                             data-placement="top" title=""
                                                                             data-original-title="编辑"></i></a>

                                <a class="btn btn-xs btn-primary"
                                   href="{{route('admin.goods.spec.value.index',['id'=>$item->id])}}">
                                    <i data-toggle="tooltip" data-placement="top"
                                       class="fa fa-pencil-square-o"
                                       title="规格管理"></i></a>

                                @if($item->id!=2)
                                    <a class="btn btn-xs btn-danger del-spec" href="javascript:;"
                                       data-href="{{route('admin.goods.spec.delete',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>
                                @endif


                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.del-spec').on('click', function () {
            var that = $(this);
            var url = that.data('href') + "?_token=" + _token;

            $.post(url, function (ret) {
                if (ret.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "该规格下面存在关联商品，不能删除!",
                        text: "",
                        type: "warning"
                    }, function () {

                    });

                }
            });

        });
    });
</script>