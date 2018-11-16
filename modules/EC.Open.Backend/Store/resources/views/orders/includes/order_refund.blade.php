<div id="order-list-table">
    <table class="table table-hover table-striped">
        <tbody>
        <!--tr-th start-->
        <tr>
            <th>售后编号</th>
            <th>售后类型</th>
            <th>申请产品</th>
            <th>数量</th>
            <th>申请时间</th>
            <th>状态</th>
        </tr>
        <!--tr-th end-->
        @foreach ($order->refunds as $refund)
            <tr>
                <td>{{$refund->refund_no}}</td>
                <td>{{$refund->TypeText}}</td>
                <td>{{$refund->orderItem->item_name}}</td>
                <td>{{$refund->quantity}}</td>
                <td>{{$refund->created_at}}</td>
                <td>{{$refund->StatusText}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- /.box-body -->
</div>