{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}--}}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/linkchecked/el.linkchecked.js') !!}

{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}

<script>

//    $('.form_datetime').datetimepicker({
//        minView: 0,
//        format: "yyyy-mm-dd hh:ii:ss",
//        autoclose: 1,
//        language: 'zh-CN',
//        weekStart: 1,
//        todayBtn: 1,
//        todayHighlight: 1,
//        startView: 2,
//        forceParse: 0,
//        showMeridian: true,
//        minuteStep: 1,
//        maxView: 4
//    });
$('#two-inputs').dateRangePicker(
        {
            separator: ' to ',
            time: {
                enabled: true
            },
            showShortcuts:false,
            language: 'cn',
            format: 'YYYY-MM-DD HH:mm',
            inline: true,
            container: '#date-range12-container',
            startDate: '{{\Carbon\Carbon::now()}}',
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

    //已选择的coupon删除
    function delete_selected(_self) {
        $(_self).parent().parent().remove();

        var select_uid=$('#select_coupons');
        var string = select_uid.val();
        var ids = string.split(',');
        var index = ids.indexOf(String($(_self).data('id')));
        if(!!~index)
        {
            ids.splice(index, 1);
        }
        var str = ids.join(',');
        select_uid.val(str);

        var count = ids.length;
        $('#selected_count').html('已选择 '+count+' 张优惠券');
    }

    //规则checkbox切换
    $("input[class='switch-input']").on('ifClicked', function (event) {
        var that = $(this);
        setTimeout(function(){
            var dom=that.parent().parent().parent();
            if(that.is(':checked')) {
                dom.find(".sw-value").show();
            } else {
                dom.find(".sw-value").hide();
            }
        },0);
    });


    //modal
    function changeSelect(_self) {
        var dom = $('#temp_select_coupons');

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
        var string = $('#temp_select_coupons').val();
        $('#select_coupons').val(string);

        if (string) {
            var count = string.split(',').length;
        } else {
            count = 0
        }

        $('#selected_count').html('已选择 '+count+' 张优惠券');
        getSelectedCoupons(string);

        $('#spu_modal').modal('hide');
    }

    function getSelectedCoupons(ids) {
        $.post('{{route('admin.marketing.getSelectCouponData')}}',
                {
                    _token:_token,
                    ids:ids
                },
                function (result) {
                    var html = '';
                    result.data.forEach(function (item) {
                        html += $.convertTemplate('#selected-coupons-temp', item, '');
                    });
                    $('.selected-coupons-list').html(html);
                }
        )
    }

</script>