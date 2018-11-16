    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            <a href="{{ route('admin.goods.model.create') }}" class="btn btn-primary margin-bottom" no-pjax>新建模型</a>

            <div class="hr-line-dashed"></div>

            <div>
                <div class="box-header with-border">
                    <h3 class="box-title">模型列表</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>模型名称</th>
                            <th>操作</th>
                        </tr>
                        <!--tr-th end-->

                        @foreach ($models as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>
                                    <a
                                            class="btn btn-xs btn-primary"
                                            href="{{route('admin.goods.model.edit',['id'=>$item->id])}}" no-pjax>
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    <a class="btn btn-xs btn-danger del-model" href="javascript:;"
                                       data-href="{{route('admin.goods.model.delete',['id'=>$item->id])}}">
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
{{--@endsection

@section('after-scripts-end')--}}
    <script>
        $(function () {
            $('.del-model').on('click', function () {
                var that = $(this);
                var url = that.data('href') + "?_token=" + _token;

                $.post(url, function (ret) {
                    if(ret.status){
                        swal({
                            title: "删除成功！",
                            text: "",
                            type: "success"
                        }, function() {
                            location.reload();
                        });
                    }else{
                        swal({
                            title: "该模型下面存在关联商品，不能删除!",
                            text: "",
                            type: "warning"
                        }, function() {

                        });

                    }
                });

            });
        });
    </script>
{{--@stop--}}