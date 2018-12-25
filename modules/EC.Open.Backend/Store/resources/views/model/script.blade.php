    <script>
        $(function () {
            @if(isset($goodsCount) AND $goodsCount>0)
            swal({
                title: "该模型下已有商品，无法修改关联规格!",
                text: "",
                type: "warning"
            }, function () {
            });
            @endif
        });

        $('#base-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        window.location = '{{route('admin.goods.model.index')}}'
                    });
                }

            }
        });

        $(".specCheckbox  input:checkbox").on('ifToggled', function (event) {
            var that = $(this);
            var url = that.data('url') + "?_token=" + _token;
            var check = that.is(':checked');
            if (check) {
                checkNum(that);
            } else {
                $.post(url, function (ret) {
                    if (ret.status) {

                    } else {
                        swal({
                            title: "改规格下面存在关联商品，不能取消!",
                            text: "",
                            type: "warning"
                        }, function () {
                            that.iCheck('check');
                        });

                    }
                });
            }
        });

        $("#spec_box  input:checkbox").on('ifToggled', function (event) {
            var that = $(this);
            var check = that.is(':checked');
            if (check) {
                checkNum(that);
            }
        });

        function checkNum(obj) {
            var num = $('#spec_box  .checked').length;
            if (num >= 2) {
                swal({
                    title: "最多选择 2 个规格!",
                    text: "",
                    type: "warning"
                }, function () {
                    obj.iCheck('uncheck');
                });
            }
        }


        //规格值html
        function getValHtml(dataValue) {
            if (dataValue == undefined)
                dataValue = '';
            return '<input class="form-control" type="text" name="attr[' + dataValue + '][name]" value=""  />';
        }

        function getDataBtnHtml(dataValue) {
            if (dataValue == undefined)
                dataValue = '';

            return '<button onclick="addValue(' + dataValue + ')"  type="button" class="btn btn-w-m btn-primary">添加参数值</button>' +
                    '<div id="value_' + dataValue + '"></div> ';
        }


        //根据显示类型返回格式
        function getTr(indexValue) {
            var specInputHtml = getValHtml(indexValue);
            var dataBtnHtml = getDataBtnHtml(indexValue);

            //数据
            var specRow = '<tr class="td_c"><td>' + specInputHtml + '</td>'
                    + '<td><select onchange="changeValueBox(this, ' + indexValue + ')" class="form-control" name="attr[' + indexValue + '][type]"><option value="1">下拉框</option><option  value="2">输入框</option></select></td>'
                    + '<td id="value_box_' + indexValue + '">' + dataBtnHtml + '</td>'
                    + '<td ><input name="attr[' + indexValue + '][is_search]" type="checkbox" value="1" /></td>'
                    + '<td ><input name="attr[' + indexValue + '][is_chart]" type="checkbox" value="1" /></td>'
                    + '<td class="del_tr"><a href="javascript:;" onclick="removeTr(this)" class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"></i></a>'
                    + '</td></tr>';

            return specRow;
        }

        function removeTr(_self) {
            $(_self).parent().parent().remove();
        }
        //删除操作
        function deltr(_self) {
            var obj = $(_self);
            swal({
                title: "确定删除该参数吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                closeOnConfirm: false
            }, function () {
                var url = obj.data('url') + "?_token=" + _token;

                $.post(url, function (ret) {
                    if (ret.status) {
                        obj.parent().parent().remove();
                        swal("删除成功!", "", "success");
                    } else {
                        swal("该参数下存在商品,不能删除!", "", "warning");
                    }
                });

            });

        }

        function delValuePhy(_self) {
            $(_self).parent().parent().parent().remove();
        }
        function delValue(_self) {

            var obj = $(_self);
            swal({
                title: "确定删除该参数值吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                closeOnConfirm: false
            }, function () {
                var url = obj.data('url') + "?_token=" + _token;

                $.post(url, function (ret) {
                    if (ret.status) {
                        obj.parent().parent().parent().remove();
                        swal("删除成功!", "", "success");
                    } else {
                        swal("该参数值下存在商品,不能删除!", "", "warning");
                    }
                });

            });

        }


        //select 切换
        function changeValueBox(obj, index) {
            var value = $(obj).children('option:selected').val();
            var dataBtnHtml = getDataBtnHtml(index);
            var emptyInput = '<input type="hidden" name="attr[' + index + '][value]">';

            if (value == 2)  //如果为输入框
            {
                $('#value_box_' + index).html(emptyInput);
            } else {
                $('#value_box_' + index).html(dataBtnHtml);
            }
        }

        //添加模型
        $('#modelsAddButton').click(
                function () {
                    var specSize = $('#spec_box tr').size() + 1;

                    var specRow = getTr(specSize);
                    $('#spec_box').append(specRow);

                    $('#spec_box').find("input[type='checkbox']").iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                        increaseArea: '20%'
                    });
                }
        );


        function addValue(index) {
            var html = '<div class="form-group">' +
                    '<div class="col-sm-10">' +
                    '<input type="text" class="form-control" name="attr[' + index + '][value][]"></div>' +
                    '<div class="col-sm-2"><label class="control-label">' +
                    '<a href="javascript:;" onclick="delValuePhy(this)" class="btn btn-xs btn-primary">' +
                    '<i class="fa fa-trash" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i>' +
                    '</a></label></div>' +
                    '</div>';

            $('#value_' + index).append(html);
        }
    </script>