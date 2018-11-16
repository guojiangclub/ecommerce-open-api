<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($groupon)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>标题</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach ($groupon as $item)
                <tr>

                    <td>{{$item->title}}</td>
                    <td>{{$item->starts_at}}</td>
                    <td>{{$item->ends_at}}</td>
                    <td>{{$item->status_text}}</td>

                    <td style="position: relative;">
                        <a no-pjax href="{{route('admin.promotion.groupon.edit',['id'=>$item->id])}}"
                           class="btn btn-xs btn-success">
                            <i class="fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top" title="编辑"></i></a>

                        <a href="javascript:" class="btn btn-xs btn-danger close-groupon"
                           data-url="{{route('admin.promotion.groupon.delete',['id'=>$item->id])}}"><i
                                    class="fa fa-trash" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="删除活动"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!! $groupon->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!! $groupon->appends(request()->except('page'))->render() !!}
        </div>

        <!-- /.box-body -->

    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>












