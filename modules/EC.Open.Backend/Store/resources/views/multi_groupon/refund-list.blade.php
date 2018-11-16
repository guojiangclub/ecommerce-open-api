<style type="text/css">
    .table-hover tr td {
        cursor: pointer
    }

    .pd10 {
        padding: 10px 0
    }
</style>

<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <div class="row">
            <div class="panel-body">

                <p>总共需要退款：{{$count}}笔，已退款{{$refundCount}}笔</p>

                <div class="table-responsive">
                    @if(count($list)>0)
                        <table class="table table-hover table-striped" style="table-layout:fixed;">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>用户</th>
                                <th>订单编号</th>
                                <th>退款金额</th>
                                <th>退款时间</th>
                                <th>交易流水号</th>
                                <th>状态</th>
                            </tr>
                            <!--tr-th end-->
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{$item->multiGrouponUser->user->mobile}}</td>
                                    <td>{{$item->multiGrouponUser->order->order_no}}</td>
                                    <td>{{$item->amount/100}}元</td>
                                    <td>{{$item->payment_time}}</td>
                                    <td>{{$item->payment_no}}</td>
                                    <td>
                                        @if($item->status=='FAILED')
                                            失败<br>
                                            {{$item->err_code_des}}
                                        @else
                                            成功
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="pull-left">
                            &nbsp;&nbsp;共&nbsp;{!! $list->total() !!} 条记录
                        </div>

                        <div class="pull-right id='ajaxpag'">
                            {!! $list->appends(request()->except('page'))->render() !!}
                        </div>

                        <!-- /.box-body -->

                    @else
                        <div>
                            &nbsp;&nbsp;&nbsp;当前无数据
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

