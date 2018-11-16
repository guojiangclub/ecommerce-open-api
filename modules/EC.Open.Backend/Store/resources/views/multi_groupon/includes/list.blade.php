<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($grouponList)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>标题</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>小团数 / 参团人数</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach ($grouponList as $item)
                <tr>
                    <td>{{$item->title}}</td>
                    <td>{{$item->starts_at}}</td>
                    <td>{{$item->ends_at}}</td>
                    <td>
                        <a href="{{route('admin.promotion.multiGroupon.grouponItemList',['id'=>$item->id])}}">
                            {{$item->items->count()}}个 / {{$item->getCountUsers()}}人
                        </a>
                    </td>
                    <td>{{$item->status_text}}</td>
                    <td style="position: relative;">
                        @if($item->edit_status==1)
                            <a no-pjax
                               href="{{route('admin.promotion.multiGroupon.edit',['id'=>$item->id,'type'=>'edit'])}}"
                               class="btn btn-xs btn-success">
                                <i class="fa fa-pencil-square-o" data-toggle="tooltip" data-placement="top"
                                   title="编辑"></i></a>

                            <a no-pjax href="javascript:;"
                               data-url="{{route('admin.promotion.multiGroupon.delete',['id'=>$item->id,'type'=>'close'])}}"
                               class="btn btn-xs btn-danger delete-groupon">
                                <i class="fa fa-close" data-toggle="tooltip" data-placement="top"
                                   title="使失效"></i></a>
                        @else
                            <a no-pjax
                               href="{{route('admin.promotion.multiGroupon.edit',['id'=>$item->id,'type'=>'show'])}}"
                               class="btn btn-xs btn-success">
                                <i class="fa fa-eye" data-toggle="tooltip" data-placement="top"
                                   title="查看"></i></a>

                            <a data-toggle="modal" class="btn btn-xs btn-warning"
                               data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                               data-url="{{route('admin.promotion.multiGroupon.getRefundModal',['groupon_id'=>$item->id])}}"
                               href="javascript:;"><i
                                        class="fa fa-money" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="操作退款"></i></a>

                            <a no-pjax
                               href="{{route('admin.promotion.multiGroupon.getRefundList',['groupon_id'=>$item->id])}}"
                               class="btn btn-xs btn-success">
                                <i class="fa fa-list-alt" data-toggle="tooltip" data-placement="top"
                                   title="查看退款记录"></i></a>

                            <a no-pjax href="javascript:" class="btn btn-xs btn-danger delete-groupon"
                               data-url="{{route('admin.promotion.multiGroupon.delete',['id'=>$item->id,'type'=>'delete'])}}"><i
                                        class="fa fa-trash" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="删除活动"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!! $grouponList->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!! $grouponList->appends(request()->except('page'))->render() !!}
        </div>

        <!-- /.box-body -->

    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>














