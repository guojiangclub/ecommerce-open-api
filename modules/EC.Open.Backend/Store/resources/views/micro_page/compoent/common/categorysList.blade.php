<table class="table table-hover table-striped" id="app" data-category_id="{{request('category_id')}}">
    <tbody>
    <!--tr-th start-->
    <tr>
        <th>ID</th>
        <th>分类</th>
        <th>操作</th>
    </tr>
    <!--tr-th end-->
    @foreach($categorys as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->name}}</td>
            <td id="radio_category_id_{{$item->id}}">

                <input id="radio_category_id_{{$item->id}}" data-category_id="{{$item->id}}"

                       type="radio" name="categorys" value="{{$item->id}}">

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
    function check(category_id){
        $('#radio_category_id_'+category_id).iCheck('check');
    }
    $('input[name=categorys]').on('ifChecked', function(e){
        var category_id=$(this).data('category_id');
        $('#app').attr('data-category_id',category_id);
    });

    function initCheck(){
        var category_id=$('#app').data('category_id');
        check("{{request('category_id')}}");
    }
    ;

</script>


