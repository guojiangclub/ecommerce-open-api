{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/linkchecked/el.linkchecked.js') !!}

<script>

    $(function () {

                @if(isset($card))
        var items = [{!! $items !!}];
                @else
        var items = [{'name': 'custom_cell1', 'key': 1}, {'name': 'custom_url', 'key': 2}, {'name': 'promotion_url', 'key': 3}];
        @endif
        $('#customDetailButton').click(function () {
            if (items.length <= 0) {
                return false;
            }

            var item = items.shift();
            var html = '<div class="custom_detail_div_' + item.key + '"><div class="supply_bonus_container"><div class="supply_bonus_container_name"><p><span style="color: red">*</span>名称：</p><input type="text" name="' + item.name + '_name" class="form-control" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" maxlength="5" required/></div><div style="clear:both;"></div><div class="supply_bonus_container_url"><p><span style="color: red">*</span>跳转连接：</p><input type="text" name="' + item.name + '" class="form-control" required/></div><div style="clear:both;"></div><div class="supply_bonus_container_tips"><p>提示语：</p><input type="text" name="' + item.name + '_tips" class="form-control" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" maxlength="5"/></div></div><i id="custom_detail_del_button" class="fa fa-trash custom_detail_del_button" data-id="' + item.key + '" data-name="' + item.name + '" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i><div style="clear:both;"></div></div>';
            var li = '<li class="msg_card_section msg_card_section_' + item.key + '"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre' + item.key + '">自定义提示语' + item.key + '</span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre' + item.key + '">自定义名称' + item.key + '</span></p></div></div></li>';
            var li_last = '<li class="msg_card_section"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre"></span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre">会员卡详情</span></p></div></div></li><li class="msg_card_section last_li"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre"></span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre">公众号</span></p></div></div></li>';


            if (1 == item.key && (0 == items.length || 1 == items.length)) {
                $('#custom_detail_container').prepend(html);
                $('#js_custom_url_preview').prepend(li);
            } else if (2 == item.key && (0 == items.length || 1 == items.length)) {
                $('.custom_detail_div_1').after(html);
                $('.msg_card_section_1').after(li);
            } else {
                $('#custom_detail_container').append(html);
                if (3 == item.key) {
                    $('.msg_card_section_2').after(li);
                } else {
                    $('#js_custom_url_preview').append(li);
                }

            }

            if (2 == items.length) {
                $('#js_custom_url_preview').append(li_last);
            }
        });

        $('body').on('click', '.custom_detail_del_button', function () {
            var key = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $(this).parent('div').remove();
            $('.msg_card_section_' + key).remove();
            items.push({'name': name, 'key': key});
            if (3 == items.length) {
                $('#js_custom_url_preview').html('');
            }
            items.sort((compare('key')));
        });


                @if(isset($card))
        var num = [{{$files_num}}];
                @else
        var num = [1, 2, 3];
        @endif
        $('#addButton').click(function () {
            if (num.length <= 0) {
                return false;
            }

            var num_item = num.shift();
            var html = '<div class="custom_field_div_' + num_item + '"><div class="supply_bonus_container"><div class="supply_bonus_container_name"><p><span style="color: red">*</span>名称：</p><input type="text" name="custom_field' + num_item + '[name]" class="form-control" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" maxlength="4" required/></div><div style="clear:both;"></div><div class="supply_bonus_container_url"><p><span style="color: red">*</span>跳转连接：</p><input type="text" name="custom_field' + num_item + '[url]" class="form-control" required/></div></div><i class="fa fa-trash del_button" data-id="' + num_item + '" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i><div style="clear:both;"></div></div>';

            var li = '';
            if ($('.top_nav_ul').find('li').length >= 1) {
                li = '<div class="div_line_' + num_item + '" style="display: block; border-right: 1px #e6e4e4 solid;"></div><li class="top_nav_li top_nav_li_' + num_item + '"><p class="top_nav_li_p top_nav_li_p_' + num_item + '">自定义</p><a class="top_nav_li_a">查看</a></li>';
            } else {
                li = '<li class="top_nav_li top_nav_li_' + num_item + '"><p class="top_nav_li_p top_nav_li_p_' + num_item + '">自定义</p><a class="top_nav_li_a">查看</a></li>';
            }

            if (1 == num_item && (0 == num.length || 1 == num.length)) {
                $('#custom_field_container').prepend(html);
                $('.top_nav_ul').prepend(li);
            } else if (2 == num_item && (0 == num.length || 1 == num.length)) {
                $('.custom_field_div_1').after(html);
                $('.top_nav_li_1').after(li);
            } else {
                $('#custom_field_container').append(html);
                $('.top_nav_ul').append(li);
            }

        });

        $('body').on('click', '.del_button', function () {
            var data_id = $(this).attr('data-id');
            $(this).parent('div').remove();
            $('.top_nav_li_' + data_id).remove();
            $('.div_line_' + data_id).remove();
            num.push(data_id);
            num.sort();
        });

        $('#centerTitleButton').click(function () {
            if ($('.center_title').length >= 1) {
                return false;
            }
            var html = '<div><div class="supply_bonus_container"><div class="supply_bonus_container_name"><p><span style="color: red">*</span>名称：</p><input type="text" name="center_title" class="form-control" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" maxlength="5" required/></div><div style="clear:both;"></div><div class="supply_bonus_container_url"><p><span style="color: red">*</span>跳转连接：</p><input type="text" name="center_url" class="form-control" required/></div></div><i class="fa fa-trash center_title_del_button" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i><div style="clear:both;"></div></div>';
            var center_title = '<a class="center_title">自定义</a>';
            $('#center_title_container').append(html);
            $('.center_title_div').append(center_title);
        });

        $('body').on('click', '.center_title_del_button', function () {
            $(this).parent('div').remove();
            $('.center_title').remove();
        });

        $.iCheckAll({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%',
            prefix: 'dep'
        });

        $('.card_cover').on('ifChanged', function () {
            $('#card_cover_tmp').val($(this).val());
            if ($(this).is(':checked') && 2 == $(this).val() && $('#bg_img').val() == '') {
                $('#error_message').show();
            } else {
                $('#error_message').hide();
            }

            var style_code = '';
            var store_logo = $('[name="store_logo"]').val();
            var store_name = $('[name="store_name"]').val();
            var card_name = $('[name="name"]').val();
            if ($(this).is(':checked') && 2 == $(this).val() && $('#bg_img').val()) {
                style_code = 'background-image: url(' + $('#bg_img').val() + ');';
                var card_html = '<div class="card-region" style="' + style_code + '"><div class="card-header"> <h4 class="shop-name"><span class="shop-logo" style="background-image:url('+store_logo+')"></span><div class="shop-name-title"><p class="card_store_name">'+store_name+'</p><p class="card_name">'+card_name+'</p></div></h4><div class="qr-code"></div></div><h3 class="member-type"></h3><div class="card-content"><p class="expiry-date">有效期：<span>无限期</span></p></div> </div>';
                $('.member-card').html(card_html);
            } else {
                var color_code = $('#bg_color').val();
                if (color_code) {
                    style_code = 'background-color: #' + color_code + ';';
                } else {
                    style_code = 'background-color: #ccc;';
                }

                var card_html = '<div class="card-region" style="' + style_code + '"><div class="card-header"> <h4 class="shop-name"><span class="shop-logo" style="background-image:url('+store_logo+')"></span> <div class="shop-name-title"><p class="card_store_name">'+store_name+'</p><p class="card_name">'+card_name+'</p></div></h4><div class="qr-code"></div></div><h3 class="member-type"></h3><div class="card-content"><p class="expiry-date">有效期：<span>无限期</span></p></div> </div>';
                $('.member-card').html(card_html);
            }
        });

        $('.dropdown-toggle-color').on('click', function () {
            $('.dropdown-menu-color').show();
        });

        $('.dropdown-menu-color li').on('click', function () {
            var color_code = $(this).children('div').attr('data-code');
            var color_code_name = $(this).children('div').attr('data-name');
            $('.card-color-show').css('background-color', '#' + color_code);
            if ($('#card_cover_tmp').val() == 1) {
                $('.card-region').css({'background-color': '#' + color_code});
            }
            $('#bg_color').val(color_code);
            $('#bg_color_name').val(color_code_name);
            $('.dropdown-menu-color').hide();
        });
    });

    function removeByValue(arr, val) {
        for(var i=0; i<arr.length; i++) {
            if(arr[i] == val) {
                arr.splice(i, 1);
                break;
            }
        }
    }

    function compare(property) {
        return function (a, b) {
            var value1 = a[property];
            var value2 = b[property];
            return value1 - value2;
        }
    }

    function OnInput(event) {
        if (event.target.name == 'store_name') {
            $('.card_store_name').html(event.target.value);
        }

        if (event.target.name == 'name') {
            $('.card_name').html(event.target.value);
        }

        if (event.target.name == 'custom_field1[name]') {
            $('.top_nav_li_p_1').html(event.target.value);
        }

        if (event.target.name == 'custom_field2[name]') {
            $('.top_nav_li_p_2').html(event.target.value);
        }

        if (event.target.name == 'custom_field3[name]') {
            $('.top_nav_li_p_3').html(event.target.value);
        }

        if (event.target.name == 'center_title') {
            $('.center_title').html(event.target.value);
        }

        if (event.target.name == 'custom_cell1_name') {
            $('.js_custom_url_name_pre1').html(event.target.value);
        }

        if (event.target.name == 'custom_cell1_tips') {
            $('.js_custom_url_tips_pre1').html(event.target.value);
        }

        if (event.target.name == 'custom_url_name') {
            $('.js_custom_url_name_pre2').html(event.target.value);
        }

        if (event.target.name == 'custom_url_tips') {
            $('.js_custom_url_tips_pre2').html(event.target.value);
        }

        if (event.target.name == 'promotion_url_name') {
            $('.js_custom_url_name_pre3').html(event.target.value);
        }

        if (event.target.name == 'promotion_url_tips') {
            $('.js_custom_url_tips_pre3').html(event.target.value);
        }

    }
    // Internet Explorer
    function OnPropChanged(event) {
        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'name') {
            $('.card_name').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_field1[name]') {
            $('.top_nav_li_p_1').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_field2[name]') {
            $('.top_nav_li_p_2').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_field3[name]') {
            $('.top_nav_li_p_3').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'center_title') {
            $('.center_title').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_cell1_name') {
            $('.js_custom_url_name_pre1').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_cell1_tips') {
            $('.js_custom_url_tips_pre1').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_url_name') {
            $('.js_custom_url_name_pre2').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'custom_url_tips') {
            $('.js_custom_url_tips_pre2').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'promotion_url_name') {
            $('.js_custom_url_name_pre3').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'promotion_url_tips') {
            $('.js_custom_url_tips_pre3').html(event.target.value);
        }
    }

</script>