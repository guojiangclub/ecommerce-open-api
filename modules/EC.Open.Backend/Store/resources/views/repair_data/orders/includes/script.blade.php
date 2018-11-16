@section('after-scripts-end')
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            minuteStep: 1
        });
        $('.form_date').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_time').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });
    </script>




    <script>
        function UrlSearch() {
            var name, value;
            var str = location.href; //取得整个地址栏
            var num = str.indexOf("?")
            str = str.substr(num + 1); //取得所有参数

            var arr = str.split("&"); //各个参数放到数组里
            for (var i = 0; i < arr.length; i++) {
                num = arr[i].indexOf("=");
                if (num > 0) {
                    name = arr[i].substring(0, num);
                    value = arr[i].substr(num + 1);
                    this[name] = value;
                }
            }
        }

        var Request = new UrlSearch(); //实例化

        if (typeof(Request.view) == "undefined") {
            Request.view = 0;
        }

        /* $('#ordersurch-form').ajaxForm({

         data:{'status':Request.view},
         success: function (result) {
         /!*$('#orders').empty();
         $('#orders').append(result);*!/

         }
         });*/

        $('#reset').on('click', function () {
            $('input[name=stime]').val('');
            $('input[name=etime]').val('');
            $('input[name=value]').val('');
        })


        function Trim(str) {
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function replaceEmptyItem(array) {
            for (var i = 0; i < array.length; i++) {
                if (array[i] == "" || typeof(array[i]) == "undefined") {
                    array.splice(i, 1);
                    i = i - 1;
                }
            }
        }

        $('.checkbox').on('ifChecked', function (event) {
            var val = $(this).val();
            $(this).parents('.order' + val).addClass('selected');
        });

        $('.checkbox').on('ifUnchecked', function (event) {
            var val = $(this).val();
            $(this).parents('.order' + val).removeClass('selected');
        });

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

        var $table = $('#order-table');
        $table.on('click', '.create_point', function () {
            var that = $(this);
            var url = that.data('url') + "?id=" + that.data('id') + "&_token=" + _token;

            swal({
                title: "您真的要给该订单操作积分吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, function () {
                $.post(url, function (res) {
                    if (res.status) {
                        swal("操作成功!", "", "success");
                        var tr_length = that.parent('td').parent('tr').siblings('tr').length;
                        if(tr_length<=2) {
                            window.location.reload();
                        } else {
                            that.parent('td').parent('tr').remove();
                        }
                    } else {
                        swal("操作失败!", "", "error");
                    }
                });
            });
        });

        $('#bitch-action').on('click',function () {
            var that=$(this);
            var num = $('.selected').length;

            if (num == 0) {
                swal("注意", "请先勾选订单再使用此功能", "warning");
                return;
            }
            var orderIds = '';
            if (num != 0) {
                for (var i = 0; i < num; i++) {
                    var gid = $('.selected').eq(i).attr('order-id');
                    orderIds += 'id[]=' + gid + '&';
                }
            }
            var url = that.data('url') + "?" + orderIds + "_token=" + _token;
            swal({
                title: "您真的要给这些订单操作积分吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, function () {
                $.post(url, function (res) {
                    if (res.status) {
                        swal("操作成功!", "", "success", function () {
                            window.localtion.reload();
                        });
                    } else {
                        swal("操作失败!", "", "error");
                    }

                });
            });

        });

        /*导出所有/筛选订单*/
        $('.export-search-orders').on('click', function () {
            var url = $(this).data('link');
            var type = $(this).data('type');
            var param = funcUrlDel('page');

            if (param == '') {
                url = url + '?type=' + type;
            } else {
                url = url + '?' + param + '&type=' + type;
            }
            $(this).data('link', url);
        });

        /*导出勾选订单*/
        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var $this = $(this),
                    href = $this.attr('href'),
                    modalUrl = $(this).data('url');

            var num = $('.selected').length;

            if (num == 0) {
                swal("注意", "请先勾选订单再使用此功能", "warning");
                return;
            }
            var orderIds = '';
            if (num != 0) {
                for (var i = 0; i < num; i++) {
                    var gid = $('.selected').eq(i).attr('order-id');
                    orderIds += 'ids[]=' + gid + '&';
                }
            }

            var url = '{{route('admin.orders.getExportData')}}';
            var type = $(this).data('type');

            url = url + '?type=' + type + '&' + orderIds;
            $(this).data('link', url);

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(modalUrl, function () {

                });
            }
        });

    </script>


@stop



