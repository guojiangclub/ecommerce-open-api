<table class="table table-hover table-striped">
    <tbody>
    <tr>
        <th>发货时间</th>
        <th>物流公司</th>
        <th>物流单号</th>
    </tr>
    @if($shipping = $order->shipping)
        <?php
        $items = $order->items;
        $shippingIds = $items->pluck('shipping_id')->toArray();
        ?>
        @foreach($shipping as $item)
            @if(in_array($item->id,$shippingIds))
                <tr>
                    <td>{{$item->delivery_time}}</td>
                    <td>{{$item->shippingMethod->name}}</td>
                    <td>{{$item->tracking}}</td>
                </tr>
            @endif
        @endforeach
    @endif
    </tbody>
</table>





