<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>昵称</th>
        <th>手机</th>
        <th>积分值</th>
        <th>状态</th>
        <th>备注</th>
        <th>动作</th>
        <th>创建日期</th>
    </tr>
    </thead>
    <tbody class="page-point-list">
        @if(count($points)>0)
            @foreach ($points as $item)
                <tr>
                    <td>{{$item->user->nick_name}}</td>
                    <td>{{$item->user->mobile}}</td>
                    <td>{{$item->value}}</td>
                    <td>
                        @if($item->status==0)
                            无效
                        @else
                            有效
                        @endif
                    </td>
                    <td>{{$item->note}}
                    @if(!empty($item->note))&nbsp;&nbsp;&nbsp;@endif
                    @if(isset($item->point_order_no)&&!empty($item->point_order_no))订单号:{{$item->point_order_no}}@endif
                    </td>


                    <td>{{$item->action}}</td>
                    <td>{{$item->created_at}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>