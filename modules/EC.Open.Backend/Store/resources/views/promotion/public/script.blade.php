{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/linkchecked/el.linkchecked.js') !!}

{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}

<script>

    $(function () {
        $('#inputDiscountTags').tagator({
            autocomplete: ['标签提示1', '标签提示2', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth']
        });
    });

//        $('.form_datetime').datetimepicker({
//            minView: 0,
//            format: "yyyy-mm-dd hh:ii",
//            autoclose: 1,
//            language: 'zh-CN',
//            weekStart: 1,
//            todayBtn: 1,
//            todayHighlight: 1,
//            startView: 2,
//            forceParse: 0,
//            showMeridian: true,
//            minuteStep: 1,
//            maxView: 4
//        });

    $('#two-inputs').dateRangePicker(
            {
                separator: ' to ',
                time: {
                    enabled: true
                },
                language: 'cn',
                format: 'YYYY-MM-DD HH:mm',
                inline: true,
                container: '#date-range12-container',
                startDate: '{{\Carbon\Carbon::now()}}',
                showShortcuts:false,
                getValue: function () {
                    if ($('#date-range200').val() && $('#date-range201').val())
                        return $('#date-range200').val() + ' to ' + $('#date-range201').val();
                    else
                        return '';
                },
                setValue: function (s, s1, s2) {
                    $('#date-range200').val(s1);
                    $('#date-range201').val(s2);
                }
            });

    $('#date-range13').dateRangePicker(
            {
                time: {
                    enabled: true
                },
                language: 'cn',
                format: 'YYYY-MM-DD HH:mm',
                inline: true,
                container: '#date-useend-container',
                startDate: '{{\Carbon\Carbon::now()}}',
                autoClose: true,
                singleDate: true

            });

    $('#date-range14').dateRangePicker(
            {
                time: {
                    enabled: true
                },
                language: 'cn',
                format: 'YYYY-MM-DD HH:mm',
                inline: true,
                container: '#date-usestart_at-container',
                startDate: '{{\Carbon\Carbon::now()}}',
                autoClose: true,
                singleDate: true

            });

    //规则checkbox切换
    $("input[class='switch-input']").on('ifClicked', function (event) {
        var that = $(this);
        setTimeout(function () {
            var dom = that.parent().parent().parent();
            if (that.is(':checked')) {
                dom.find(".sw-value").show();
                dom.find(".sw-hold").hide();
            } else {
                dom.find(".sw-value").hide();
                dom.find(".sw-hold").show();
            }
        }, 0);
    });


    //    //等级选择
    //    $("#allGroup  input").on('ifClicked', function(event){
    //        $(this).on('ifChecked', function () {
    //            $('.groupID').each(function () {
    //                $(this).iCheck('disable');
    //            });
    //        });
    //
    //        $(this).on('ifUnchecked', function () {
    //            $('.groupID').each(function () {
    //                $(this).iCheck('enable');
    //            });
    //        });
    //
    //    });


    //更改table文案提示
    $("#discount-type  input").on('ifClicked', function (event) {
        var value = $(this).val();
        var text = value == 'order_amount' ? '元' : '件';

        $(".unit").each(function () {
            $(this).html(text);
        });
    });

    //选择SKU切换
    $("#discount-sku  input").on('ifClicked', function (event) {
        var value = $(this).val();
        var takeSku = $('.takeSku');
        value == 'all' ? takeSku.css('display', 'none') : takeSku.css('display', 'block');
    });


    //折叠展示分类
    function displayData(_self) {
        if (_self.alt == "关闭") {
            jqshow($(_self).parent().parent().attr('id'), 'hide');
            $(_self).attr("src", "{!! url('assets/backend/images/open.gif') !!}");
            _self.alt = '打开';
        }
        else {
            jqshow($(_self).parent().parent().attr('id'), 'show');
            $(_self).attr("src", "{!! url('assets/backend/images/close.gif') !!}");
            _self.alt = '关闭';
        }
    }

    function jqshow(id, isshow) {
        var obj = $("#category_list_table tr[parent='" + id + "']");
        if (obj.length > 0) {
            obj.each(function (i) {
                jqshow($(this).attr('id'), isshow);
            });
            if (isshow == 'hide') {
                obj.hide();
            }
            else {
                obj.show();
            }
        }
    }

    function actionChange(_self) {
        var value = $(_self).children('option:selected').val();

        if (value == 'order_fixed_discount') {
            var action_html = $('#discount_action_template').html();
        }

        if (value == 'goods_fixed_discount') {
            var action_html = $('#goods_discount_action_template').html();
        }

        if (value == 'order_percentage_discount') {
            var action_html = $('#percentage_action_template').html();
        }

        if (value == 'goods_percentage_discount' || value == 'goods_percentage_by_market_price_discount') {
            var action_html = $('#goods_percentage_action_template').html();
        }

        $('#promotion-action').html(action_html.replace(/{VALUE}/g, '0'));
    }

</script>