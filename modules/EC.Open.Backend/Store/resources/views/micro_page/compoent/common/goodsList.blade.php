<table class="table table-hover table-striped" id="app" data-goods_id="{{request('goods_id')}}" data-totalPage="{{$goods->lastPage()}}">
    <tbody>
    <!--tr-th start-->
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>销售价</th>
        <th>库存</th>
        <th>操作</th>
    </tr>
    <!--tr-th end-->
    @foreach($goods as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td><img src="{{$item->img}}" width="30" height="30"> &nbsp; {{$item->name}}</td>
            <td>{{$item->sell_price}}</td>
            <td>{{$item->store_nums}}</td>
            <td id="radio_goods_id_{{$item->id}}">

                <input id="radio_goods_id_{{$item->id}}" data-goods_id="{{$item->id}}"

                       type="radio" name="goods" value="{{$item->id}}">

            </td>
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
    function check(goods_id){
        $('#radio_goods_id_'+goods_id).iCheck('check');
    }
    $('input[name=goods]').on('ifChecked', function(e){
        var goods_id=$(this).data('goods_id');
        $('#app').attr('data-goods_id',goods_id);
    });

    function initCheck(){
        var goods_id=$('#app').data('goods_id');
        check("{{request('goods_id')}}");
    }
    ;

    //initCheck();
</script>


