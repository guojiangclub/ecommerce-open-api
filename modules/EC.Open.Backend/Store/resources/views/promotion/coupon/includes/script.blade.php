<script>

    function changeSelect(_self) {
        var dom = $('#temp_select_users');

        if ($(_self).hasClass('select')) {

            var btnVal = $(_self).data('id');
            var string = dom.val();
            var ids = string.split(',');
            var index = ids.indexOf(String(btnVal));

            if(!!~index)
            {
                ids.splice(index, 1);
            }

            var str = ids.join(',');
            $(_self).removeClass('select btn-info').addClass('btn-warning unselect').find('i').removeClass('fa-check').addClass('fa-times');

            dom.val(str);

        } else {
            var btnVal = $(_self).data('id');
            var str = dom.val() + ',' + btnVal;

            if (str.substr(0, 1) == ',') str = str.substr(1);

            $(_self).addClass('select btn-info').removeClass('btn-warning unselect').find('i').addClass('fa-check').removeClass('fa-times');

            dom.val(str);
        }
        paraDiscount.ids = str;
        console.log(paraDiscount.ids);
    }

    function sendIds() {
        var string = $('#temp_select_users').val();
        $('#select_users').val(string);

        if (string) {
            var count = string.split(',').length;
        } else {
            count = 0
        }

        $('#selected_count').html('已选择 '+count+' 个用户');
        getSelectedUsers(string);

        $('#modal').modal('hide');
    }

    function getSelectedUsers(ids) {
        $.post('{{route('admin.promotion.coupon.getSelectedUsersByID')}}',
                {
                    _token:_token,
                    ids:ids
                },
                function (result) {
                    var html = '';
                    result.data.forEach(function (item) {
                        html += $.convertTemplate('#selected-user-temp', item, '');
                    });
                    $('.selected-users-list').html(html);
                }
        )
    }
</script>