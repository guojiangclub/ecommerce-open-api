<script language="javascript">
    //折叠展示
    function displayData(_self) {
        if (_self.alt == "关闭") {
            jqshow($(_self).parent().parent().attr('id'), 'hide');
            $(_self).attr("src", "{!! url('assets/backend/images/open.gif') !!}");
            _self.alt = '打开';
        }
        else {
            jqshow($(_self).parent().parent().attr('id'), 'show');
            $(_self).attr("src", "{!! url('assets/backend/images/close.gif') !!}");
            _self.alt = '关闭';
        }
    }

    function jqshow(id, isshow) {
        var obj = $("#list_table tr[parent='" + id + "']");
        if (obj.length > 0) {
            obj.each(function (i) {
                jqshow($(this).attr('id'), isshow);
            });
            if (isshow == 'hide') {
                obj.hide();
            }
            else {
                obj.show();
            }
        }
    }

    //排序
    function toSort(id) {
        if (id != '') {
            var va = $('#s' + id).val();
            var part = /^\d+$/i;
            if (va != '' && va != undefined && part.test(va)) {
                $.post("{{ route('admin.image-category.category_sort') }}", {
                    'id': id,
                    'sort': va,
                    _token: $('meta[name="_token"]').attr('content')
                }, function (data) {
                    if (data.status) {
                        swal({
                            title: "修改排序成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal("修改排序失败!", "", "error");
                    }
                });
            }
        }
    }

    function checkCategory(id) {
        swal({
            title: "确认删除该图片分组吗？",
            text: "删除分组，该分组下面的子分组也会一并删除",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            deleteCategory(id);
        });
    }

    function deleteCategory(id) {
        $.post("{{ route('admin.image-category.delete') }}", {
            'id': id,
            _token: $('meta[name="_token"]').attr('content')
        }, function (data) {
            if (data.status) {
                swal({
                    title: "删除成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location.reload();
                });
            } else {
                swal("删除失败!", "", "error");
            }
        });
    }
</script>