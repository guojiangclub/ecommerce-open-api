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
     * 导出优惠券使用记录搜索结果
     */
    $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
        var $this = $(this),
                href = $this.attr('href'),
                modalUrl = $(this).data('url');

        var param = funcUrlDel('page');

        var url = '{{route('admin.promotion.coupon.getUsedExportData')}}';
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

</script>