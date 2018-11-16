{!! Html::script('assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script('assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script('assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}

{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}
<script>

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

    $("input[class='type']").on('ifUnchecked', function (event) {
        $(this).parent().parent().find('input[type="text"]').val('');
    });

    /* 添加折扣数据方式切换 */
    $('.add-data').click(function () {
        var that = $(this);
        var type = that.data('type');
        that.addClass('btn-danger').siblings().removeClass('btn-danger');
        $('input[name="data_type"]').val(type);

        if (type == 'single') {
            $('#single-list-box').removeClass('hidden');
            $('#upload-box').addClass('hidden');

        } else {
            $('#single-list-box').addClass('hidden');
            $('#upload-box').removeClass('hidden');
        }


    });

    //添加层级
    var discont_html = $('#discount_template').html();
    $('#add-condition').click(function () {
        var num = $('#discount-table tbody').find('tr[class="showData"]').length;


        $('#discount-table tbody').append(discont_html.replace(/{NUM}/g, num));
        $('#discount-table tbody').find("input[type = 'radio']").iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%'
        });

        $("input[class='type']").on('ifUnchecked', function (event) {
            $(this).parent().parent().find('input[type="text"]').val('');
        });
    });

    //删除操作
    function deltr(_self) {
        $(_self).parent().parent().remove();
        if ($(_self).prev().val()) {
            var delIDs = $('#delID').val();
            var sku = $(_self).prev().val();
            $('#delID').val(delIDs + ',' + sku);

        }
    }

    //
    function radioEvents() {
        $('#discount-table input[type=radio]').on("ifToggled", function (e) {
//            debugger;
            var radio = $(e.target);
            var checked = radio.is(':checked');
            var input = radio.parents('label').find('input[type=text]');
            var value = input.val();

            if (checked) {
                value = input.attr('data-old-value');
                input.val(value);
            } else {
                input.attr('data-old-value', value);
                input.val("");
            }
        });
    }

    //伪分页
    function conditionPages() {
        $('.pagination').show();
        $(".page-discount-list tr:gt(14)").hide();//初始化，前面4条数据显示，其他的数据隐藏。
        var total_q = $(".page-discount-list tr").length;//总数据
        var current_page = 15;//每页显示的数据
        var current_num = 1;//当前页数
        var total_page = Math.round(total_q / current_page) == 0 ? 1 : Math.round(total_q / current_page);//总页数
        var next = $(".next");//下一页
        var prev = $(".prev");//上一页
        $(".dis_total").text(total_page);//显示总页数
        $(".dis_current_page").text(current_num);//当前的页数

        //下一页
        $(".dis_next").click(function () {
            if (current_num == total_page) {
                return false;//如果大于总页数就禁用下一页
            }
            else {
                $(".dis_current_page").text(++current_num);//点击下一页的时候当前页数的值就加1
                $.each($('.page-discount-list tr'), function (index, item) {
                    var start = current_page * (current_num - 1);//起始范围
                    var end = current_page * current_num;//结束范围
                    if (index >= start && index < end) {//如果索引值是在start和end之间的元素就显示，否则就隐
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
        //上一页方法
        $(".dis_prev").click(function () {
            if (current_num == 1) {
                return false;
            } else {
                $(".dis_current_page").text(--current_num);
                $.each($('.page-discount-list tr'), function (index, item) {
                    var start = current_page * (current_num - 1);//起始范围
                    var end = current_page * current_num;//结束范围
                    if (index >= start && index < end) {//如果索引值是在start和end之间的元素就显示，否则就隐藏
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

        })
    }

    $(function () {
        var uploader = WebUploader.create({

            // 选完文件后，是否自动上传。
            auto: true,
            swf: '{{url('libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('upload.excel',['_token'=>csrf_token()])}}',
            pick: '#filePicker',
            fileVal: 'upload_excel',
            accept: {
                title: 'Excel',
                extensions: 'xlsx,xlsm,xls'
//                    mimeTypes: 'image/*'
            }
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file, response) {
            /* $('#' + file.id).addClass('upload-state-done');*/
            /*$('#' + file.id).append('<input type="hidden" name="banner_pic" value="' + response.url + '" >');*/
            $('.update_true').html("文件上传成功");
            $("input[name='upload_excel']").val(response.file);
            $('.addbut').attr('type', 'submit');
            $('.addbut').removeClass('disabled');


        });

    })

</script>