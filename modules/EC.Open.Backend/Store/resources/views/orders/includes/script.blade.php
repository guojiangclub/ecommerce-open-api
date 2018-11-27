    <script type="text/javascript">

        $(function () {
            $('#dist-target').distpicker({
                province: '{{request('province')}}',
                city: '{{request('city')}}'
            });
            $.getScript('{{env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js'}}',function () {
                $.fn.datetimepicker.dates['zh-CN'] = {
                    days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
                    daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
                    daysMin: ["日", "一", "二", "三", "四", "五", "六", "日"],
                    months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    today: "今天",
                    suffix: [],
                    meridiem: ["上午", "下午"]
                };

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

            });

        });
    </script>




    <script>
        function UrlSearch() {
            var name, value;
            var str = location.href; //取得整个地址栏
            var num = str.indexOf("?");
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


        $('#reset').on('click', function () {
            $('input[name=stime]').val('');
            $('input[name=etime]').val('');
            $('input[name=s_pay_time]').val('');
            $('input[name=e_pay_time]').val('');
            $('input[name=value]').val('');
            $('input[name=stotal]').val('');
            $('input[name=etotal]').val('');
            $('#dist-target').distpicker('reset');
            $('select').val('');
        });


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
            $(this).parents('.user_' + val).addClass('selected');
        });

        $('.checkbox').on('ifUnchecked', function (event) {
            var val = $(this).val();
            $(this).parents('.order' + val).removeClass('selected');
            $(this).parents('.user_' + val).removeClass('selected');
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

        $table.on('click', '.close-order', function () {

            var url = $(this).data('url') + "?_token=" + _token;

            swal({
                title: "您真的要关闭该订单吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "关闭",
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, function () {
                $.post(url, function (res) {
                    if (res.status) {
                        swal("订单关闭成功!", "", "success", function () {

                        });
                        location.reload();
                    } else {
                        swal("订单关闭失败!", "", "error");
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
            console.log(url);
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
                if ('split' == '{{request('status')}}') {
                    for (var i = 0; i < num; i++) {
                        var uid = $('.selected').eq(i).attr('data-uid');
                        orderIds += 'ids[]=' + uid + '&';
                    }
                    var supplier = $("#supplier option:selected").val();
                    if (supplier == undefined) {
                        supplier = '';
                    }
                    orderIds += 'status=split&supplier=' + supplier;
                } else {
                    for (var i = 0; i < num; i++) {
                        var gid = $('.selected').eq(i).attr('order-id');
                        orderIds += 'ids[]=' + gid + '&';
                    }
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


