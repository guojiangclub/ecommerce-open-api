<script>

    function changeSelect(_self, action) {
        if(action == 'exclude' || action == 'view_exclude')
        {
            var dom = $('#exclude_spu');
        } else {
            var dom = $('#selected_spu');
        }

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
    }

    function sendIds(action) {
        if(action == 'exclude' || action == 'view_exclude')
        {
            var string = $('#exclude_spu').val();
        } else {
            var string = $('#selected_spu').val();
        }


        if (string) {
            var count = string.split(',').length;
        } else {
            count = 0
        }

        if(action == 'exclude' || action == 'view_exclude')
        {
            $('.countExcludeSpu').html(count);
        } else {
            $('.countSpu').html(count);
        }


        $('#spu_modal').modal('hide');
    }




</script>