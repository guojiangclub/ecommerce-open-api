<table class="table table-hover table-striped">
    <tbody>
    @if(isset($orderLog) && $orderLog->count()>0)
    <tr>
        <th>操作者</th>
        <th>动作</th>
        <th>操作时间</th>
        <th>订单号</th>
        <th>备注</th>
    </tr>
        @foreach($orderLog as $item)
        <tr>
            <td>{!! \App\User::find($item->user_id)->name!!}</td>
            <td>{{$item->action}}</td>
            <td>{{$item->created_at}}</td>
            <td>{{$item->order_no}}</td>
            <td>{{$item->note}}</td>
        </tr>
        @endforeach
    @endif
    </tbody>
</table>
