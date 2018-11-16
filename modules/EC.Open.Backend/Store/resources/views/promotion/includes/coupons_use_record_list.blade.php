<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if($coupons->count()>0)
    <table class="table table-hover table-striped">
        <tbody>
        <!--tr-th start-->
        <tr>
            <th><input type="checkbox" class="check-all"></th>
            <th>生成时间</th>
            <th>优惠券码</th>
            <th>订单编号</th>
            <th>订单金额</th>
            <th>会员名</th>
            <th>摘要</th>
        </tr>
        <!--tr-th end-->
        @foreach ($coupons as $coupon)
            <tr class="coupon_use_record{{$coupon->id}}">
                <td><input class="checkbox"   type="checkbox" value="{{$coupon->id}}" name="ids[]"></td>
                <td>{{$coupon->created_at}}</td>
                <td>{{$coupon->coupon_code}}</td>
                <td></td>
                <td></td>
                {{--@foreach($coupon['relations'] as $item)
                        <td>{{$coupon->coupon_code}}</td>
                        <td>{{$item->order_no}}</td>
                        <td>{{$item->order_amount}}</td>
                @endforeach--}}
                    <td>{!! \App\User::find($coupon->user_id)->name!!}</td>
                    <td>{{$coupon->note}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-left">
        &nbsp;&nbsp;共&nbsp;{!! $coupons->total() !!} 条记录
    </div>

    <div class="pull-right">
        {!! $coupons->render() !!}
    </div>

    <!-- /.box-body -->

    @else
    <div>
        &nbsp;&nbsp;&nbsp;当前无数据
    </div>
    @endif
</div>












