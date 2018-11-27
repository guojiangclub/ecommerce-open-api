<script>
    function getList(val) {
        $('.pages').pages({
            page: 1,
            url: val,
            get: $.http.get.bind($.http),
            marks: {
                total: 'data.last_page',
                index: 'data.current_page',
                data: 'data'
            }
        }, function (data) {

            if (data.total == 0) {
                $('#no-data').html('当前无数据');
            }

            var html = '';

            data.data.forEach(function (item) {
                if (item.status == 0) {
                    item.statusText = '无效';
                } else {
                    item.statusText = '有效';
                }

                if (item.point_order_no) {
                    item.note = item.note + ',订单号：' + item.point_order_no;
                }

                html += $.convertTemplate('#page-temp', item, '');
            });
            $('.page-point-list').html(html);
        });
    }

    $(document).ready(function () {
        var getUrl = '{{route('admin.users.getUserPointList',['id' =>$user->id])}}';
        getList(getUrl);

        $('.viewPoint').on('click', function () {
            var that = $(this);
            that.addClass('btn-success').siblings('.viewPoint').removeClass('btn-success');
            var url = that.data('url');
            getList(url);
        });

    });

    $('#submit-integral').on('click', function () {
        var data = {
            value: $("input[name='value']").val(),
            note: $("input[name='note']").val(),
            user_id: $("input[name='user_id']").val(),
            _token: _token
        };

        $.post('{{route('admin.users.addPoint')}}', data, function (result) {
            swal({
                title: "操作成功！",
                text: "",
                type: "success"
            }, function () {
                location.reload();
            });
        });

    });
</script>