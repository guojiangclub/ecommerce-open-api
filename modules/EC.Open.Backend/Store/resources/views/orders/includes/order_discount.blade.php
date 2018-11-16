<table class="table table-hover table-striped">
    <tbody>
    <tr>

        <th>所享优惠描述</th>
        <th>优惠金额</th>

        <th>所用优惠券码</th>
    </tr>
        @foreach($adjustments as $item)
            <tr>
                <td>{{$item->label}}</td>
                <td>{{$item->amount/100}}</td>
                <td>{!! $item->coupon_code !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>