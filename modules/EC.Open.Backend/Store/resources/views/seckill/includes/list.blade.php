<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($seckill)>0)
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
            @foreach ($seckill as $item)
                <tr>

                    <td>{{$item->title}}</td>
                    <td>{{$item->starts_at}}</td>
                    <td>{{$item->ends_at}}</td>
                    <td>{{$item->status_text}}</td>

                    <td style="position: relative;">

                        <a no-pjax href="{{route('admin.promotion.seckill.edit',['id'=>$item->id])}}"
                           class="btn btn-xs btn-success">
                            <i class="fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top" title="编辑"></i></a>
                        @if($item->check_status==1 OR $item->check_status==0)
                            <a no-pjax href="javascript:;"
                               data-url="{{route('admin.promotion.seckill.delete',['id'=>$item->id,'type'=>'close'])}}"
                               class="btn btn-xs btn-danger close-seckill">
                                <i class="fa fa-close" data-toggle="tooltip" data-placement="top"
                                   title="使失效"></i></a>
                        @else
                            <a href="javascript:" class="btn btn-xs btn-danger close-seckill"
                               data-url="{{route('admin.promotion.seckill.delete',['id'=>$item->id])}}"><i
                                        class="fa fa-trash" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="删除活动"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!! $seckill->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!! $seckill->appends(request()->except('page'))->render() !!}
        </div>

        <!-- /.box-body -->

    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>












