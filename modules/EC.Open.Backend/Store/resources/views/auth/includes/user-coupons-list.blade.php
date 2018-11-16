
@if(count($coupons)>0)
    <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
        <tr>
            <th>时间</th>
            <th>优惠券码</th>
            <th>是否已使用</th>
            <th>涉及订单号</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        @foreach($coupons as $coupon)
            <tr>
                <td>{{$coupon->created_at}}</td>
                <td>{{$coupon->coupon_code}}</td>
                <td>
                    @if($coupon->is_use==0)
                        <label class="label label-danger">No</label>
                     @elseif($coupon->is_use==1)
                        <label class="label label-success">Yes</label>
                    @endif
                </td>
                <td>{{!empty(\App\Entities\Order::find($coupon->order_id)->order_no)?\App\Entities\Order::find($coupon->order_id)->order_no:"/"}}</td>
                <td>{{$coupon->note}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
@endif
    <div class="pull-left">
        共&nbsp;{!! $coupons->total() !!} 条记录
    </div>






