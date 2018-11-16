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


    $('#reset').on('click',function () {
        $('input[name=begin_time]').val('');
        $('input[name=end_time]').val('');
        $('input[name=stime]').val('');
        $('input[name=etime]').val('');
        $('input[name=value]').val('');
        $('input[name=order_no]').val('');
    });



    /**
     * 导出促销使用记录搜索结果
     */
    $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
        var $this = $(this),
                href = $this.attr('href'),
                modalUrl = $(this).data('url');

        var param = funcUrlDel('page');

        var url = '{{route('admin.promotion.discount.getUsedExportData')}}';
        var type = $(this).data('type');

        url = url + '?' + param + '&type=' + type;


        $(this).data('link', url);

        if (modalUrl) {
            var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
            $target.modal('show');
            $target.html('').load(modalUrl, function () {

            });
        }
    });





    //促销活动使用记录列表批量
    {{--$('#recordSaleBatchExport').on('click',function () {--}}
        {{--if($('#coupons table').length<=0){--}}
            {{--swal("操作失败", "当前无可数据", "warning");--}}
            {{--return false;--}}
        {{--}--}}
        {{--var excel=[];--}}
        {{--var title= ['促销描述','订单编号', '订单总金额', '订单状态', '用户名','使用时间'];--}}
        {{--var num= $('.selected').length;--}}

        {{--if(num==0){--}}
            {{--swal("注意", "请勾选需要导出的数据列", "warning")--}}
            {{--return false;--}}
        {{--}--}}
        {{--$('.batch').ladda().ladda('start');--}}
        {{--for(var i=0;i<num;i++){--}}
            {{--var couponarr=[];--}}
            {{--couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;--}}
{{--//            couponarr[i]=$('.selected').eq(i).text();//;--}}
            {{--excel[i]=couponarr[i];--}}
            {{--excel[i]=getSplitString(excel[i]).split(",");--}}
{{--//            excel[i][0]=excel[i][0]+'/'+excel[i][1];--}}
{{--//            excel[i][1]='';--}}
            {{--replaceEmptyItem(excel[i]);--}}
            {{--excel[i][5]=excel[i][5]+' '+excel[i][6];--}}
            {{--excel[i][6]='';--}}
            {{--excel[i].pop();--}}
        {{--}--}}
        {{--$.ajax({--}}
            {{--type: 'POST',--}}
            {{--url: "{{route('admin.promotion.excelExport')}}",--}}
            {{--data:{date:excel,title:title,name:'Discount_'},--}}
            {{--success: function(date){--}}
                {{--window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;--}}
                {{--setTimeout(function(){--}}
                    {{--$('.batch').ladda().ladda('stop')--}}
                {{--},2000)--}}
            {{--}});--}}

    {{--});--}}

    //促销全导出
    {{--$('#sale_use_export').on('click',function () {--}}
        {{--if($('#coupons table').length<=0){--}}
            {{--swal("操作失败", "当前无可数据", "warning");--}}
            {{--return false;--}}
        {{--}--}}
        {{--$('#sale_use_export').ladda().ladda('start');--}}
        {{--var excel=[];--}}
        {{--var title=['促销描述','订单编号', '订单总金额', '订单状态', '用户名','使用时间'];--}}
        {{--var num= $('.selected').length;--}}

        {{--if(num==0){--}}
            {{--if(location.href.indexOf("?") < 0 ){--}}
                {{--var url=location.href+"?excel=1";--}}
            {{--}else{--}}
                {{--var url=location.href+"&excel=1";--}}
            {{--}--}}

            {{--$.ajax({--}}
                {{--type: 'GET',--}}
                {{--url: url,--}}
                {{--success: function(date){--}}

                    {{--window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;--}}
                    {{--setTimeout(function(){--}}
                        {{--$('#sale_use_export').ladda().ladda('stop')--}}
                    {{--},2000)--}}
                {{--}});--}}
            {{--return false;--}}
        {{--}--}}

{{--// 已勾选--}}
        {{--for(var i=0;i<num;i++){--}}
            {{--var couponarr=[];--}}
            {{--couponarr[i]=Trim($('.selected').eq(i).text()).replace(/[\r\n]/g,"");//;--}}
            {{--excel[i]=couponarr[i];--}}
            {{--excel[i]=getSplitString(excel[i]).split(",");--}}
            {{--excel[i][0]=excel[i][0]+'/'+excel[i][1];--}}
            {{--excel[i][1]='';--}}
            {{--replaceEmptyItem(excel[i]);--}}
        {{--}--}}

        {{--$.ajax({--}}
            {{--type: 'POST',--}}
            {{--url: "{{route('admin.promotion.excelExport')}}",--}}
            {{--data:{date:excel,title:title},--}}
            {{--success: function(date){--}}
                {{--window.location.href="{{route('admin.promotion.download',['url'=>''])}}"+"/"+date;--}}
                {{--setTimeout(function(){--}}
                    {{--$('#coupons_use_export').ladda().ladda('stop')--}}
                {{--},2000)--}}
            {{--}});--}}

    {{--});--}}
</script>