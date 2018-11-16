    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            <a href="{{ route('brand.create') }}" class="btn btn-primary margin-bottom" no-pjax>新建品牌</a>

            <div>

                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>品牌名称</th>
                            <th>品牌LOGO</th>
                            <th>品牌网站</th>
                            <th>品牌描述</th>
                            <th>排序</th>
                            <th>是否显示</th>
                            <th>操作</th>
                        </tr>
                        <!--tr-th end-->

                        @foreach ($brand as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td><img src="{{$item->logo}}" style="max-width: 80px;max-height: 80px;"/></td>
                                <td>{{$item->url}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->sort}}</td>
                                <td>@if($item->is_show==1) 是 @else 否 @endif</td>
                                <td>
                                    <a
                                            class="btn btn-xs btn-primary"
                                            href="{{route('brand.edit',['id'=>$item->id])}}" no-pjax>
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-pencil-square-o"
                                           title="编辑"></i></a>
                                    <a data-method="delete" class="btn btn-xs btn-danger"
                                       href="{{route('brand.destroy',['id'=>$item->id])}}">
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