<table class="table table-hover table-striped">
    <tbody>

    <tr>
        <th>描述</th>
        <th>积分值</th>

        {{--<th>所用优惠券码</th>--}}
    </tr>
    @foreach($orderPoint as $item)
        <tr>
            {{--<td>@if($item->orgin_type=='coupon')优惠券 @elseif($item->orgin_type=='discount')折扣@endif</td>--}}

            <td>{{$item->note}}</td>
            <td>{{$item->value}}</td>
        </tr>
    @endforeach

    @foreach($orderConsumePoint as $item)
        <tr>
            <td>{{$item->note}}</td>
            <td>{{$item->value}}</td>
        </tr>
    @endforeach

    </tbody>
</table>