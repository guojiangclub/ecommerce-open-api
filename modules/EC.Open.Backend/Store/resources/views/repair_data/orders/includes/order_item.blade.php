<div id="order-list-table">
    <table class="table table-hover table-striped">
        <tbody>
        <!--tr-th start-->
        <tr>
            <th>ID</th>
            <th>订单编号</th>
            <th>收货人姓名</th>
            <th>收货地址</th>
            <th>联系人电话</th>
            <th>会员名</th>
            <th>订单状态</th>
            {{--<th>支付状态</th>--}}
            <th>发货状态</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        <!--tr-th end-->
        @foreach ($orders as $order)
            <tr>
                <th>{{$order->id}}</th>
                <td>{{$order->order_no}}</td>
                <td>{{$order->accept_name}}</td>
                <td>{{$order->address}}</td>
                <td>{{$order->mobile}}</td>
                <td>{!! \App\User::find($order->user_id)->name!!}</td>
                <td>
                    @if($order->status==7)
                        退款中
                    @elseif($order->status==1)
                        待付款
                    @elseif($order->status==2)
                        待发货
                    @elseif($order->status==3)
                        配送中待收货
                    @elseif($order->status==4)
                        已收货待评价
                    @elseif($order->status==5)
                        已完成
                    @elseif($order->status==6)
                        已取消
                    @endif
                </td>
                </td>
                {{--<td>{{$order->pay_status==1?'已付款':'未付款'}}</td>--}}
                <td>{{$order->distribution_status==1?'已发货':'未发货'}}</td>

                <td>{{$order->created_at}}</td>
                <td style="position: relative;">
                    <a target="_blank" href="{{route('admin.orders.show',['id'=>$order->id])}}"
                       class="btn btn-xs btn-success">
                        <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看"></i></a>

                    @if($order->status==2)
                        <a class="btn btn-xs btn-success" id="chapter-create-btn" data-toggle="modal"
                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.orders.deliver',['id'=>$order->id])}}">
                            <i class="fa fa-share" data-toggle="tooltip" data-placement="top"
                               title="发货"></i></a>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right id='ajaxpag'">
        {!! $orders->appends(['view'=>$view])->render() !!}
    </div>

    <!-- /.box-body -->
</div>