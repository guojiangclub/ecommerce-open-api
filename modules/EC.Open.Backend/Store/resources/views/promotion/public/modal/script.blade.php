<script>

    function changeSelect(_self, action) {
        if(action == 'exclude' || action == 'view_exclude')
        {
            var dom = $('#temp_exclude_spu');
        } else {
            var dom = $('#temp_selected_spu');
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
        paraDiscount.ids = str;
    }

    function changeGroupSelect(_self, action) {
	    if(action == 'exclude' || action == 'view_exclude')
	    {
		    var dom = $('#temp_exclude_group');
	    } else {
		    var dom = $('#temp_selected_group');
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

	    paraDiscount.ids = str;
    }

    function sendIds(action) {
        if(action == 'exclude' || action == 'view_exclude')
        {
            var string = $('#temp_exclude_spu').val();
            $('#exclude_spu').val(string);
        } else {
            var string = $('#temp_selected_spu').val();
            $('#selected_spu').val(string);
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

    function sendGroupIds(action)
    {
	    if(action == 'exclude' || action == 'view_exclude')
	    {
		    var string = $('#temp_exclude_group').val();
		    $('#exclude_group').val(string);
	    } else {
		    var string = $('#temp_selected_group').val();
		    $('#selected_group').val(string);
	    }

	    if (string) {
		    var count = string.split(',').length;
	    } else {
		    count = 0
	    }

	    if(action == 'exclude' || action == 'view_exclude')
	    {
		    $('.countExcludeGroup').html(count);
	    } else {
		    $('.countGroup').html(count);
	    }


	    $('#wechat_modal').modal('hide');
    }
</script>