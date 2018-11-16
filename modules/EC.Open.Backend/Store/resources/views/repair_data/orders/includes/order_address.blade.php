
<table class="table table-hover table-striped">
    <tbody>
    <tr>
        <th>收货人</th>
        <th>电话</th>
        <th>收货地址</th>
        <th>快递方式</th>
        <th>发货单号</th>
        <th>收货时间</th>
    </tr>
    <tr>
        <td>{{$order->accept_name}}</td>
        <td><i class="fa fa-mobile"></i>&nbsp;{{$order->mobile}}</td>
        <td><i class="fa fa-home">&nbsp;</i>{{$order->address_name}}  {{$order->address}}</td>
        <td>{{!empty($shipping->shippingMethod->name)?$shipping->shippingMethod->name:'/'}}</td>
        <td>{{!empty($shipping->tracking)?$shipping->tracking:'/'}}</td>
        <td>{{$order->accept_time ? $order->accept_time:'/'}}</td>
    </tr>
    </tbody>
</table>






