<script>
    function changeSelect(_self) {
        var dom = $('#temp_selected_spu');

        if ($(_self).hasClass('select')) {

            var btnVal = $(_self).data('id');
            var string = dom.val();
            var ids = string.split(',');
            var index = ids.indexOf(String(btnVal));

            if (!!~index) {
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
    }

    function sendIds() {
        var selected = $('#selected_spu').val();
        var string = $('#temp_selected_spu').val();
        var length = $('#select-goods-box tr:last-child').data('key');
        $('#selected_spu').val(string);

        $('#modal').modal('hide');
        if (string) {
            $.get('{{route('admin.promotion.seckill.getSelectGoods')}}', {
                ids: string,
                select: selected,
                num: length
            }, function (result) {
                $('#select-goods-box').append(result);
                $('#select-goods-box').find("input").iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });
            });
        }

    }
</script>