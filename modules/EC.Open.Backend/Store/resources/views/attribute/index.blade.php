    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            <a href="{{ route('admin.goods.attribute.create') }}" class="btn btn-primary margin-bottom" no-pjax>新建参数</a>

            <div class="hr-line-dashed"></div>

            <div>
                <div class="box-header with-border">
                    <h3 class="box-title">商品参数列表</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>参数名称</th>
                            <th>操作方式</th>
                            <th>操作</th>
                        </tr>
                        <!--tr-th end-->

                        @foreach ($attributes as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>
                                    {{$item->type == 2?'输入框':'下拉列表'}}
                                </td>
                                <td>
                                    <a
                                            class="btn btn-xs btn-primary"
                                            href="{{route('admin.goods.attribute.edit',['id'=>$item->id])}}" no-pjax>
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    <a class="btn btn-xs btn-danger del-attr" href="javascript:;"
                                       data-href="{{route('admin.goods.model.deleteAttr',['id' =>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>
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
            $('.del-attr').on('click', function () {
                    var obj = $(this);
                    swal({
                        title: "确定删除该参数吗?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "删除",
                        closeOnConfirm: false
                    }, function () {
                        var url = obj.data('href') + "?_token=" + _token;

                        $.post(url, function (ret) {
                            if(ret.status){
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                });
                            }else{
                                swal("该参数下存在商品,不能删除!", "", "warning");
                            }
                        });

                    });

            });
        });
    </script>