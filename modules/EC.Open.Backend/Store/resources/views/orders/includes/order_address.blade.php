<table class="table table-hover table-striped">
    <tbody>
    <tr>
        <th>收货人</th>
        <th>电话</th>
        <th>收货地址</th>
        <th>收货时间</th>
    </tr>
    <tr>
        <td>{{$order->accept_name}}</td>
        <td><i class="fa fa-mobile"></i>&nbsp;{{$order->mobile}}</td>
        <td><i class="fa fa-home">&nbsp;</i>{{$order->address_name}}  {{$order->address}}</td>

        <td>{{$order->accept_time ? $order->accept_time:'/'}}</td>
    </tr>
    </tbody>
</table>

@if($order->pay_status==1 AND $order->distribution_status==0 AND !session('admin_check_supplier'))
    <a data-toggle="modal" class="btn btn-primary"
       data-target="#modal" data-backdrop="static" data-keyboard="false"
       data-url="{{route('admin.orders.editAddress',['id'=>$order->id])}}"
       href="javascript:;">修改收货地址</a>
@endif






