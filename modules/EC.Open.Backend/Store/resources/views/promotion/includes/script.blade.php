{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/linkchecked/el.linkchecked.js') !!}

<script>

    $('.form_datetime').datetimepicker({
        minView: 0,
        format: "yyyy-mm-dd hh:ii:ss",
        autoclose: 1,
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: true,
        minuteStep: 1,
        maxView: 4
    });

//    $('#goodsSku').tagator({
//       // autocomplete: ['标签提示1', '标签提示2', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth']
//    });

    $("input[class='type']").on('ifUnchecked', function (event) {
       $(this).parent().parent().find('input[type="text"]').val('');
    });

    //兑换码切换
    $("input[name='base[coupon_based]']").on('ifClicked', function (event) {
       var value = $(this).val();
        if(value == 1){
            $('#code').show();
        }else{
            $('#code').hide();
        }
    });


    //等级选择
    $("#allGroup  input").on('ifClicked', function(event){
        $(this).on('ifChecked', function () {
            $('.groupID').each(function () {
                $(this).iCheck('disable');
            });
        });

        $(this).on('ifUnchecked', function () {
            $('.groupID').each(function () {
                $(this).iCheck('enable');
            });
        });

    });


    //添加规则
    var rules_html = $('#rules_template').html();
    $('#add-rules').click(function() {
        var num = $('.promotion_rules_box').length;
        $('#rules_box').append(rules_html.replace(/{NUM}/g, num+1));
    });

    //删除操作
    function delRules(_self){
        $(_self).parent().remove();
    }

    //更改table文案提示
    $("#discount-type  input").on('ifClicked', function(event){
        var value = $(this).val();
        var text = value == 'order_amount'?'元':'件';

        $(".unit").each(function () {
            $(this).html(text);
        });
    });

    //选择SKU切换
    $("#discount-sku  input").on('ifClicked', function(event){
        var value = $(this).val();
        var takeSku = $('.takeSku');
        value == 'all'?takeSku.css('display','none'):takeSku.css('display','block');
    });


    //rules select下拉动作
    function rulesChange(_self) {
        var value = $(_self).children('option:selected').val();
        var num = $(_self).data('num');

        if (value == 'item_total') {
            var configuration_html = $('#rules_item_total_template').html();
            $('#promotion_rules_' + num).find('#configuration_' + num).html(configuration_html.replace(/{NUM}/g, num));

        }

        if (value == 'cart_quantity') {
            var configuration_html = $('#rules_cart_quantity_template').html();
            $('#promotion_rules_' + num).find('#configuration_' + num).html(configuration_html.replace(/{NUM}/g, num));

        }

        if (value == 'contains_product') {
            var configuration_html = $('#rules_sku_template').html();
            $('#promotion_rules_' + num).find('#configuration_' + num).html(configuration_html.replace(/{NUM}/g, num));
           // $('.goodsSku').tagator({});
        }

        if (value == 'contains_role') {
            var configuration_html = $('#rules_role_template').html();
            $('#promotion_rules_' + num).find('#configuration_' + num).html(configuration_html.replace(/{NUM}/g, num));
        }

        if (value == 'contains_category') {
            $.post('{{route('admin.promotion.getCategory')}}', {num:num,_token: _token}, function (ret) {
                $('#promotion_rules_' + num).find('#configuration_' + num).html(ret);

                $.iCheckAll({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%',
                    prefix: 'dep'
                });
            });

        }
    }

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

        if(value == 'order_fixed_discount') {
            var action_html = $('#discount_action_template').html();
        }

        if(value == 'goods_fixed_discount') {
            var action_html = $('#goods_discount_action_template').html();
        }

        if(value == 'order_percentage_discount') {
            var action_html = $('#percentage_action_template').html();
        }

        if(value == 'goods_times_point') {
            var action_html = $('#goods_times_point_action_template').html();
        }

        if(value == 'goods_percentage_discount' || value == 'goods_percentage_by_market_price_discount') {
            var action_html = $('#goods_percentage_action_template').html();
        }

        $('#promotion-action').html(action_html.replace(/{VALUE}/g, '0'));
    }
    $('#batchExport').on('click',function () {
        if($('#coupons table').length<=0){
            swal("操作失败", "当前无可数据", "warning");
            return false;
        }
        var excel=[];
        var title=['优惠券名','面额','开始时间','截止时间','限领/人','条件','发行量','领取率','状态','创建人'];
        var num= $('.selected').length;
        if(num==0){
            swal("注意", "请勾选需要批量导出的列", "warning");
            return false;
        }
        $('.batch').ladda().ladda('start');
//已勾选
        for(var i=0;i<num;i++){
            var couponarr=[];
            couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;
            excel[i]=couponarr[i];
            excel[i]=getSplitString(excel[i]).split(",");
            excel[i][2]=excel[i][2]+'/'+excel[i][3];
            excel[i][4]=excel[i][4]+'/'+excel[i][5];
            excel[i][3]='';
            excel[i][5]='';
            replaceEmptyItem(excel[i]);
        }

        $.ajax({
            type: 'POST',
            url: "{{route('admin.promotion.excelExport')}}",
            data:{date:excel,title:title},
            success: function(date){
                window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                setTimeout(function(){
                    $('.batch').ladda().ladda('stop')
                },2000)
            }});

    });



    $('.checkbox').on('ifChecked', function(event){
        var val=$(this).val();
        $(this).parents('.coupon'+val).addClass('selected');
    });

    $('.checkbox').on('ifUnchecked', function(event){
        var val=$(this).val();
        $(this).parents('.coupon'+val).removeClass('selected');
    });
    function replaceEmptyItem(array) {
        for(var i = 0;i<array.length;i++)
        {
            if(array[i] == "" || typeof(array[i]) == "undefined")
            {
                array.splice(i,1);
                i= i-1;
            }

        }
    }


    $('#reset').on('click',function () {
        $('input[name=begin_time]').val('');
        $('input[name=end_time]').val('');
        $('input[name=stime]').val('');
        $('input[name=etime]').val('');
        $('input[name=value]').val('');
    })

    function getSplitString(str) {
        var arr = str.split(",");

        var resources = "";
        for (var i = 0; i < arr.length; i++) {
            var arr1 = arr[i].split(/\s+/);

            for (var j = 0; j < arr1.length; j++) {
                if (jQuery.trim(arr1[j]) != "") {
                    resources += jQuery.trim(arr1[j]) + ",";
                }
            }
        }
        return resources;
    }

    function Trim(str)
    {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    //优惠券使用记录列表批量
    $('#recordBatchExport').on('click',function () {
        if($('#coupons table').length<=0){
            swal("操作失败", "当前无可数据", "warning");
            return false;
        }
        var excel=[];
        var title=['生成时间','优惠券名','优惠券码','订单编号','订单总金额','订单状态','用户名','摘要'];
        var num= $('.selected').length;

        if(num==0){
            swal("注意", "请勾选需要导出的数据列", "warning")
            return false;
        }
        $('.batch').ladda().ladda('start');
        for(var i=0;i<num;i++){
            var couponarr=[];
            couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;
//            couponarr[i]=$('.selected').eq(i).text();//;
            excel[i]=couponarr[i];
            excel[i]=getSplitString(excel[i]).split(",");
            excel[i][0]=excel[i][0]+'/'+excel[i][1];
            excel[i][1]='';
            replaceEmptyItem(excel[i]);
        }

        $.ajax({
            type: 'POST',
            url: "{{route('admin.promotion.excelExport')}}",
            data:{date:excel,title:title},
            success: function(date){
                window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                setTimeout(function(){
                    $('.batch').ladda().ladda('stop')
                },2000)
            }});

    });

    //优惠券全导出
    $('#coupons_use_export').on('click',function () {
        if($('#coupons table').length<=0){
            swal("操作失败", "当前无可数据", "warning");
            return false;
        }
        $('#coupons_use_export').ladda().ladda('start');
        var excel=[];
        var title=['生成时间','优惠券名','优惠券码','订单编号','订单总金额','订单状态','用户名','摘要'];
        var num= $('.selected').length;

        if(num==0){
            if(location.href.indexOf("?") < 0 ){
                var url=location.href+"?excel=1";
            }else{
                var url=location.href+"&excel=1";
            }

            $.ajax({
                type: 'GET',
                url: url,
                success: function(date){

                    window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                    setTimeout(function(){
                        $('#coupons_use_export').ladda().ladda('stop')
                    },2000)
                }});
            return false;
        }

// 已勾选
        for(var i=0;i<num;i++){
            var couponarr=[];
            couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;
            excel[i]=couponarr[i];
            excel[i]=getSplitString(excel[i]).split(",");
            excel[i][0]=excel[i][0]+'/'+excel[i][1];
            excel[i][1]='';
            replaceEmptyItem(excel[i]);
        }

        $.ajax({
            type: 'POST',
            url: "{{route('admin.promotion.excelExport')}}",
            data:{date:excel,title:title},
            success: function(date){
                window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                setTimeout(function(){
                    $('#coupons_use_export').ladda().ladda('stop')
                },2000)
            }});

    });

    //促销活动使用记录列表批量
    $('#recordSaleBatchExport').on('click',function () {
        if($('#coupons table').length<=0){
            swal("操作失败", "当前无可数据", "warning");
            return false;
        }
        var excel=[];
        var title= ['促销描述','订单编号', '订单总金额', '订单状态', '用户名','使用时间'];
        var num= $('.selected').length;

        if(num==0){
            swal("注意", "请勾选需要导出的数据列", "warning")
            return false;
        }
        $('.batch').ladda().ladda('start');
        for(var i=0;i<num;i++){
            var couponarr=[];
            couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;
//            couponarr[i]=$('.selected').eq(i).text();//;
            excel[i]=couponarr[i];
            excel[i]=getSplitString(excel[i]).split(",");
//            excel[i][0]=excel[i][0]+'/'+excel[i][1];
//            excel[i][1]='';
            replaceEmptyItem(excel[i]);
            excel[i][5]=excel[i][5]+' '+excel[i][6];
            excel[i][6]='';
            excel[i].pop();
        }
        $.ajax({
            type: 'POST',
            url: "{{route('admin.promotion.excelExport')}}",
            data:{date:excel,title:title,name:'Discount_'},
            success: function(date){
                window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                setTimeout(function(){
                    $('.batch').ladda().ladda('stop')
                },2000)
            }});

    });

    //促销全导出
    $('#sale_use_export').on('click',function () {
        if($('#coupons table').length<=0){
            swal("操作失败", "当前无可数据", "warning");
            return false;
        }
        $('#sale_use_export').ladda().ladda('start');
        var excel=[];
        var title=['促销描述','订单编号', '订单总金额', '订单状态', '用户名','使用时间'];
        var num= $('.selected').length;

        if(num==0){
            if(location.href.indexOf("?") < 0 ){
                var url=location.href+"?excel=1";
            }else{
                var url=location.href+"&excel=1";
            }

            $.ajax({
                type: 'GET',
                url: url,
                success: function(date){

                    window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                    setTimeout(function(){
                        $('#sale_use_export').ladda().ladda('stop')
                    },2000)
                }});
            return false;
        }

// 已勾选
        for(var i=0;i<num;i++){
            var couponarr=[];
            couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;
            excel[i]=couponarr[i];
            excel[i]=getSplitString(excel[i]).split(",");
            excel[i][0]=excel[i][0]+'/'+excel[i][1];
            excel[i][1]='';
            replaceEmptyItem(excel[i]);
        }

        $.ajax({
            type: 'POST',
            url: "{{route('admin.promotion.excelExport')}}",
            data:{date:excel,title:title},
            success: function(date){
                window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;
                setTimeout(function(){
                    $('#coupons_use_export').ladda().ladda('stop')
                },2000)
            }});

    });
</script>