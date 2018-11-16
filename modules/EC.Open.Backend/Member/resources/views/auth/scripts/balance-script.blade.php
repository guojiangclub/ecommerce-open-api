<script>
    function getBalanceList(val) {
        $('.balance-pages').pages({
            page: 1,
            url: val,
            get: $.http.get.bind($.http),
            marks: {
                total: 'data.last_page',
                index: 'data.current_page',
                data: 'data'
            }
        }, function (data) {

            if(data.total == 0){
                $('#no-balance-data').html('当前无数据');
            }

            var html = '';

            data.data.forEach(function (item) {
                item.value=item.value/100;
	            item.current_balance=item.current_balance/100;
                html += $.convertTemplate('#page-balance-temp', item, '');
            });
            $('.page-balance-list').html(html);
        });
    }

    $(document).ready(function () {
        var getUrl = '{{route('admin.users.edit.balance.list',['id' =>$user->id])}}';
        getBalanceList(getUrl);
    });

    $('.operateBalance').on('click',function () {
       var data={
           value:$("input[name='balance_value']").val(),
           note:$("input[name='balance_note']").val(),
           user_id:$("input[name='user_id']").val(),
           _token:_token
       };

        $.post('{{route('admin.users.edit.balance.operateBalance')}}',data,function (result) {
           if(!result.status){
               swal('错误','余额值必须是数字且大于0','error');
           }else{
               swal({
                   title: "操作成功！",
                   text: "",
                   type: "success"
               }, function() {
                   location.reload();
               });
           }
        });

    });
</script>