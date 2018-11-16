<table class="table table-hover table-striped">
    <tbody>
    <tr>
        <th>发票抬头</th>
        <th>发票类型</th>
        <th>发票内容</th>
        <th>金额</th>
        <th>发票编号</th>
        <th>开票时间</th>
    </tr>
    <tr>
        @if($order->invoiceOrder)
            <td>{{$order->invoiceOrder->title}}</td>
            <td>{{$order->invoiceOrder->type}}</td>
            <td>{{$order->invoiceOrder->content}}</td>
            <td>{{$order->invoiceOrder->amount}}</td>
            <td>{{$order->invoiceOrder->number}}</td>
            <td>{{$order->invoiceOrder->invoice_at}}</td>
        @else
            <td colspan="4">无需发票</td>
        @endif
    </tr>
    </tbody>
</table>
