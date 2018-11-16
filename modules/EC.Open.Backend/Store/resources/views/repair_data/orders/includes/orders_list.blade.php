<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($orders)>0)
        <table id="order-table" class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th><input type="checkbox" class="check-all"></th>
                <th>订单编号</th>
                <th>订单类型</th>
                <th>下单会员</th>
                <th>收货人</th>
                <th>订单状态</th>
                <th>售后状态</th>
                <th>商品数量</th>
                <th>总金额</th>
                <th>应付金额</th>
                <th>下单时间</th>
                <th style="width: 150px;">操作</th>
            </tr>
            <!--tr-th end-->
            @foreach ($orders as $order)
                <tr class="order{{$order->id}}" order-id="{{$order->id}}">
                    <td><input class="checkbox" type="checkbox" value="{{$order->id}}" name="ids[]"></td>
                    <td>{{$order->order_no}}</td>
                    <td>{{$order->type == 0 ? '普通' : ($order->type == 1 ? '折扣' : ($order->type == 2 ? '内购' : '其他'))}}</td>
                    <td><a href="{{route('admin.users.edit', $order->user_id)}}">
                            @if($user=$order->user)
                                @if($user->name)
                                    {{$user->name}}
                                @elseif($user->mobile)
                                    {{$user->mobile}}
                                @elseif($user->nick_name)
                                    {{$user->nick_name}}
                                @else
                                    /
                                @endif
                            @else
                                /
                            @endif

                        </a>
                    </td>
                    <td>
                        {{!empty($order->accept_name)?$order->accept_name:'/'}}&nbsp;&nbsp;&nbsp;&nbsp;<i
                                class="fa fa-mobile"></i>&nbsp;{{!empty($order->mobile)?$order->mobile:'/'}}
                    </td>

                    <td>{{$order->StatusText}}</td>
                    <td>{{$order->refund_status}}</td>
                    <td>{{$order->count}}</td>
                    <td>{{$order->items_total}}</td>
                    <td>{{$order->total}}</td>
                    <td>{{$order->created_at}}</td>
                    <td style="position: relative;">
                        {{--<a href="{{route('admin.orders.show',['id'=>$order->id])}}"--}}
                           {{--class="btn btn-xs btn-success">--}}
                            {{--<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看"></i></a>--}}

                        <a data-url="{{route('admin.repair.order.createPoint')}}" data-id="{{$order->id}}"
                           class="btn btn-xs btn-success create_point" href="javascript:;">
                            <i class="fa fa-send" data-toggle="tooltip" data-placement="top" title="给积分"></i></a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!! $orders->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!! $orders->appends(request()->except('page'))->render() !!}
        </div>

        <!-- /.box-body -->

    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>












