{{--<link href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://apps.bdimg.com/libs/fontawesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" >

{!! Html::style('libs/AdminLTE/dist/css/AdminLTE.min.css') !!}
{!! Html::style('libs/AdminLTE/dist/css/skins/_all-skins.min.css') !!}
{!! Html::style('css/common/typo.css') !!}
{!! Html::style('libs/sweetalert/sweetalert.css') !!}
{!! Html::style('libs/iCheck/square/blue.css') !!}
{!! Html::style('css/backend/main.css') !!}--}}


@if(count($orders)>0)
    <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
        <tr>
            <th>时间</th>
            <th>订单号</th>
            <th>动作</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{$order->created_at}}</td>
                <td>{{$order->order_no}}</td>
                <td>
                    {{$order->action}}
                </td>
                <td>{{$order->note}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
    <div class="pull-left">
        共&nbsp;{!! $orders->total() !!} 条记录
    </div>







