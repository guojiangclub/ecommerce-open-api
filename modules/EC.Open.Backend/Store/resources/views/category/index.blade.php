
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary margin-bottom">添加分类</a>

            <!-- /.row -->

            <div class="dataTable_wrapper">
                <table class="table table-striped table-hover" id="list_table">
                    <thead>
                    <tr>
                        <th>排序</th>
                        <th>分类名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr id="{{ $category->id }}" parent="{{ $category->parent_id }}">
                            <td><input style="width: 45px" id="s{{ $category->id }}" value="{{ $category->sort }}"
                                       class="form-control" type="text" size="2"
                                       onblur="toSort( {{ $category->id }} );"></td>
                            <td>
                                <img style='margin-left:{{ ($category->level - 1) * 20 }}px' class="operator"
                                     src="{!! url('assets/backend/images/close.gif') !!}" onclick="displayData(this);" alt="关闭"/>
                                {{ $category->name }}
                            </td>

                            <td>
                                <a href="{{ route('admin.category.edit',['id' => $category->id]) }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="编辑"></i>
                                </a>
                                <button onclick="checkCategory({{$category->id}})" class="btn btn-xs btn-danger">
                                    <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
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
                    $.get("{{ route('admin.category.category_sort') }}", {
                        'id': id,
                        'sort': va,
                        _token: _token
                    }, function (data) {
                        if (data.status) {
                            swal({
                                title: "修改分类排序成功！",
                                text: "",
                                type: "success"
                            }, function() {
                                location.reload();
                            });
                        } else {
                            swal("修改分类排序失败!", "", "error");
                        }
                    });
                }
            }
        }

        function checkCategory(id) {
            $.get("{{ route('admin.category.check') }}", {
                'id': id,
                _token: _token
            }, function (data) {
                if (data.status) {
                    deleteCategory(id);
                } else {
                    swal('注意','该分类下存在商品,无法删除','warning');

                }
            });
        }

        function deleteCategory(id) {
            $.post("{{ route('admin.category.delete') }}", {
                'id': id,
                _token: _token
            }, function (data) {
                if (data.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location.reload();
                    });
                } else {
                    swal("删除失败!", "该分类存在子分类", "error");
                }
            });
        }
    </script>
