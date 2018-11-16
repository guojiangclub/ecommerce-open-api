{{--@section('after-scripts-end')--}}
    <script>
        //选择全部
        $('.check-full').on('ifChecked', function (e) {
            $('#goods-table').find(".icheckbox_square-green").iCheck('check');
        });

        $('.check-full').on('ifUnchecked', function (e) {
            $('#goods-table').find(".icheckbox_square-green").iCheck('uncheck');
        });


        //单选操作
        $('.checkbox').on('ifChecked', function (event) {
            var val = $(this).val();
            $(this).parents('.goods' + val).addClass('selected');
        });

        $('.checkbox').on('ifUnchecked', function (event) {
            var val = $(this).val();
            $(this).parents('.goods' + val).removeClass('selected');
        });


        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var $this = $(this),
                    num = $('.selected').length,
                    modalUrl = $(this).data('url');
            if (num == 0) {
                swal("注意", "未选中商品", "warning");
                return;
            }
            var goodsIds = '';
            if (num != 0) {
                for (var i = 0; i < num; i++) {
                    var gid = $('.selected').eq(i).data('id');
                    goodsIds += 'ids[]=' + gid + '&';
                }
            }

            if ($(".check-full").is(':checked')) {
                goodsIds = 'ids=all';
            }

            url = modalUrl + '&' + goodsIds;

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(url, function () {

                });
            }
        });

    </script>

{{--@stop--}}