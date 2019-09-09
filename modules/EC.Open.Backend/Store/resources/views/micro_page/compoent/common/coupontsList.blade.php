<table class="table table-hover table-striped" id="app" data-coupon_id="{{request('coupon_id')}}" data-totalPage="{{$coupons->lastPage()}}">
    <tbody>
    <!--tr-th start-->
    <tr>
        <th>ID</th>
        <th>优惠券</th>
        <th>优惠</th>
        <th>操作</th>
    </tr>
    <!--tr-th end-->
    @foreach($coupons as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->title}}</td>
            <td>
                @foreach($item->discountActions as $val)
                    {{$val->action_text}}<br>
                @endforeach
            </td>
            <td id="radio_coupon_id_{{$item->id}}">

                <input id="radio_coupon_id_{{$item->id}}" data-coupon_id="{{$item->id}}"

                       type="radio" name="coupons">

            </td>

            <input id="coupon_id_{{$item->id}}"  type="hidden"
                   value="{{$item->id}}" data-title="{{$item->title}}"

                    data-value="{{$item->action_type['value']}}"

                   data-type="{{$item->action_type['type']}}" data-time="{{$item->use_start_time}}--{{$item->use_end_time}}">
        </tr>
    @endforeach
    </tbody>
</table>

<script>

    $('#app').find("input").iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%'
    });
    function check(coupon_id){
        $('#radio_coupon_id_'+coupon_id).iCheck('check');
    }
    $('input[name=coupons]').on('ifChecked', function(e){
        var coupon_id=$(this).data('coupon_id');
        $('#app').attr('data-coupon_id',coupon_id);
    });

    function initCheck(){
        var coupon_id=$('#app').data('coupon_id');
        check("{{request('coupon_id')}}");
    }
    ;

</script>


