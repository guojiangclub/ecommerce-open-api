<script>
    $('.form_datetime').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minuteStep : 1
    });


    /**
     * 导出搜索结果
     */
    $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
        var $this = $(this),
                href = $this.attr('href'),
                modalUrl = $(this).data('url');

        var param = funcUrlDel('page');

        if (param == '' && num == 0) {
            swal("注意", "请先进行搜索再使用此功能", "warning");
            return;
        }

        var url = '{{route('admin.card.getExportData')}}';
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