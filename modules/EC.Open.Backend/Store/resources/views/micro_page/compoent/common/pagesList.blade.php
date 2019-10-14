<table class="table table-hover table-striped" id="app" data-page_id="{{request('page_id')}}" data-totalPage="{{$pages->lastPage()}}">
    <tbody>
    <!--tr-th start-->
    <tr>
        <th>ID</th>
        <th>微页面名称</th>
        <th>操作</th>
    </tr>
    <!--tr-th end-->
    @foreach($pages as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->name}}</td>
            <td id="radio_page_id_{{$item->id}}">

                <input id="radio_page_id_{{$item->id}}" data-page_id="{{$item->id}}"

                       type="radio" name="pages" value="{{$item->id}}">

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
    function check(page_id){
        $('#radio_page_id_'+page_id).iCheck('check');
    }
    $('input[name=pages]').on('ifChecked', function(e){
        var page_id=$(this).data('page_id');
        $('#app').attr('data-page_id',page_id);
    });

    function initCheck(){
        var page_id=$('#app').data('page_id');
        check("{{request('page_id')}}");
    }
    ;

</script>


