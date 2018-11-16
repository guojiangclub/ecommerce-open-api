<script>
    @if($spec_id==2)
    $(document).ready(function () {

        var input = document.createElement('input');
        input.setAttribute('name', 'add_value[0][rgb]');
        input.setAttribute('value', '444444');
        input.setAttribute('class', 'form-control');
        var picker = new jscolor(input);
        picker.fromString('444444');
        $('#rgb_0').append(input);
    });
    @endif

    $('#spec-value-form').ajaxForm({
        success: function (result) {
            if (result.status) {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location.reload();
                });
            } else {
                swal('存在重复的规格值', '', 'error');
            }

        }
    });


    //颜色
    function getColorHtml(specSize) {
        var input = document.createElement('input');
        input.setAttribute('name', 'add_value[' + specSize + '][rgb]');
        input.setAttribute('class', 'form-control');

        var picker = new jscolor(input);

        picker.fromHSV(255, 255, 255);

        var sel = ' <select class="form-control" name="add_value[' + specSize + '][color]">' +
                @foreach($color as $value)
                        '<option value="{{$value}}">{{$value}}</option>' +
                @endforeach
                        '</select>';

        $("#color_" + specSize).append(sel);

        $("#rgb_" + specSize).append(input);

    }


    //根据显示类型返回格式
    function getTr(indexValue) {
        //数据
        var specRow = '<tr class="td_c"><td>' +
                '<input type="text" class="form-control" name="add_value[' + indexValue + '][name]" />' +
                '</td>' +

                @if($spec_id == 2)
                        '<td id="rgb_' + indexValue + '"></td>' +
                '<td id="color_' + indexValue + '"></td>' +
                @endif

                        '<td><a href="javascript:;" class="btn btn-xs btn-primary operatorPhy">' +
                '<i class="fa fa-trash" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i></a></td></tr>';

        return specRow;
    }

    //预定义

    $('#spec_box tr').each(
            function (i) {
                initButton(i);
            }
    );


    //添加规格按钮(点击绑定)
    $('#specAddButton').click(
            function () {

                var specSize = $('#spec_box tr').size();

                var specRow = getTr(specSize);

                $('#spec_box').append(specRow);

                @if($spec_id == 2)
                getColorHtml(specSize);
                @endif

            initButton(specSize);
            }
    );

    //按钮(点击绑定)
    function initButton(indexValue) {
        //功能操作按钮
        $('#spec_box tr:eq(' + indexValue + ') .operator').click(function () {
            var obj = $(this);
            swal({
                title: "确定删除该规格值吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                closeOnConfirm: false
            }, function () {
                var url = obj.data('url');
                var data = {
                    id: obj.data('id'),
                    _token: _token
                };

                $.post(url, data, function (ret) {
                    if (ret.status) {
                        obj.parent().parent().remove();
                        swal("删除成功!", "", "success");
                    } else {
                        swal("改规格值下面存在商品，不能删除!", "", "warning");
                    }
                });

            });
        });

        $('#spec_box tr:eq(' + indexValue + ') .operatorPhy').click(function () {
            $(this).parent().parent().remove();
        });
    }
</script>
