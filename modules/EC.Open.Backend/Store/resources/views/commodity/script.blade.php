{!! Html::script(env("APP_URL").'/assets/backend/libs/pop.js?v=20180807') !!}
@if(isset($goods_info))
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/spec/Sortable.js?v=201806041167') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/spec/spec.js?v=201806041167') !!}
    <script>
        $(function () {
            window.skuBuilder.init(<?php echo json_encode($specData, JSON_UNESCAPED_UNICODE);  ?>);
        });
    </script>
    @endif
            <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            autoHeightEnabled: false,
            initialFrameHeight: 500
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.

        });

        var uepc = UE.getEditor('containerpc', {
            autoHeightEnabled: false,
            initialFrameHeight: 500
        });
        uepc.ready(function () {
            uepc.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });


        $("#upload_container").on("click", function () {
            var el_list = $(this);
            $.addImages(el_list, "selectHighlight");
        });

        $("#upload_containerpc").on("click", function () {
            console.log(123);
            var el_list = $(this);
            $.addImages(el_list, "selectHighlight");
        });
    </script>
    <script>
        @if(isset($goods_info))
           $('input[name="is_del"]').on('ifClicked', function (event) {
            var that = $(this);
            var id = $('input[name="id"]').val();
            var checkUrl = '{{route('admin.goods.checkPromotionStatus')}}';
            if (that.val() == 2) {
                $.post(checkUrl, {token: _token, id: id}, function (result) {
                    if (result.status) {
                        return true;
                    } else {
                        swal({
                                    title: "提示",
                                    text: "该商品正在参与促销活动，确认下架吗？",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "确认",
                                    cancelButtonText: "取消",
                                    closeOnConfirm: false
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        swal.close();
                                        return true;
                                    } else {
                                        $('#radio_online').iCheck('check');
                                        that.iCheck('uncheck');
                                    }
                                });
                    }
                });
            }

        });
        @endif

        function switchTab(i) {
            current_action = i;
            $(".nav-tabs li").removeClass("active").eq(i - 1).addClass("active");
            $(".tab-pane").removeClass("active").eq(i - 1).addClass("active");
        }
        $('#base-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.error, "error");
                    if (result.error_key == 'sku' || result.error_key.search('_spec.') != -1) {
                        switchTab(2);
                    } else if (result.error_key == '_imglist') {
                        switchTab(5);
                    } else {
                        switchTab(1);
                    }

                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = decodeURIComponent('{{(isset($redirect_url) AND $redirect_url)?$redirect_url:route('admin.goods.index')}}');
                    });
                }

            }

        });

        /**
         * 设置商品默认图片
         */
        function defaultImage(_self) {
            $('.filelist img').removeClass('current');
            $(_self).addClass('current');
            $('input[name="img"]').val($('.filelist img[class="current"]').attr('src'));
        }


        //根据模型动态生成扩展属性
        function create_attr(model_id) {
            $('#speccontent').html("");
            var tempUrl = '{{route('admin.goods.getAttribute')}}';
            var postData = {
                model_id: model_id,
                _token: _token
            };

            $.get(tempUrl, postData, function (ret) {
                $('#propert_table').html(ret);
            });


            //spec data
            var tempUrl2 = '{{route('admin.goods.getSpecsData')}}';
            var postData2 = {
                model_id: model_id,
                _token: _token
            };

            $.get(tempUrl2, postData2, function (ret) {
                if (ret.status) {
                    var specData = ret.data.specs;
                    window.skuBuilder.init(specData);

                }
            });

        }
    </script>

    <script type="text/html" id="template">
        <div class="category-wrap">
            <input data-id="{#id#}" data-parent="{#parent_id#}" data-name="{#value#}"
                   data-uniqueId="categoryIds_{#id#}" class="category_checks" type="checkbox"/>
            &nbsp;&nbsp;&nbsp;
            <input class="btn btn-outline btn-primary category-btn" type="button" value="{#value#}"/>
        </div>
    </script>

    <script>
        $(function () {
            var category_checked = [];
            var category_ids = [];
            // 初始化
            function initCategory() {
                category_checked = [];
                category_ids = [];
                var data = {
                    _token: _token
                };
                $.get('{{route('admin.goods.get_category')}}', data, function (html) {
                    $('#category-box').children().remove();
                    $('#category-box').append(html);
                    $('#category-box').find("input").iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                        increaseArea: '20%'
                    });
                });
            }

            @if(!isset($goods_info))
                initCategory();
            @endif

            $("#hidden-category-id input").each(function () {
                category_ids.push(parseInt($(this).val()));
            });
            category_checked = $(".category_name").text().split("/");

            initTheOrderCheckedCats();

            function moveTheOrderCat($parentObject, template) {
                if ($parentObject.length == 1) {
                    $parentObject.children('ul').append(template);
                } else {
                    $(".category_name").children('ul').append(template);
                }
            }

            function initTheOrderCheckedCats() {
                $(".category_name li").each(function () {
                    var parentId = $(this).data('parent');
                    var $parentObject = $(".category_name").find('[data-id=' + parentId + ']');
                    moveTheOrderCat($parentObject, $(this));
                });
            }

            function addTheOrderCheckedCat(dataId, dataParentId, dataName) {
                var whetherExistNode = $(".category_name").find('[data-id=' + dataId + ']').length;
                if (0 == whetherExistNode) {
                    var template = " <li data-id=" + dataId + " data-parent=" + dataParentId + "><span>" + dataName +
                            "</span><ul></ul>" +
                            " </li>";
                    var $parentObject = $(".category_name").find('[data-id=' + dataParentId + ']');
                    moveTheOrderCat($parentObject, template);
                }
            }

            function removeTheOrderCheckedCat(dataId) {
                var $node = $(".category_name").find('[data-id=' + dataId + ']');
                var $childrenNode = $node.children('ul').children();
                if ($childrenNode.length > 0) {
                    var $nodeParent = $node.parents('li').first();
                    moveTheOrderCat($nodeParent, $childrenNode);
                }
                $node.remove();
            }

            function operator($object, parentId, parentName, flag) {
                // $flag =1 表示checked操作， $flag=2 表示unchecked操作， $flag=3表示点击钮
                // $object 表示 category-content类对象

                // 首先 写unchecked操作
                if (2 == flag) {
                    // 在category_ids里面找parentId
                    var positionIndex = category_ids.indexOf(parentId);
                    category_ids.splice(positionIndex, 1);

                    // 同上， 将parentName从category_checked中移除
                    positionIndex = category_checked.indexOf(parentName);
                    category_checked.splice(positionIndex, 1);

                    //将表单中的hidden 某个category_id移除
                    $("#hidden-category-id").find("#category_" + parentId).remove();
                } else {
                    // 在flag =1 或者 flag=3时 一定会向后台请求数据
                    // html
                    var html = "";
                    //var groupId = $("select[name=category_group]").children('option:selected').val();
                    var data = {
                        "parentId": parentId,
                        //"groupId": groupId,
                        "type-click-category-button": true
                    };
                    $.get(
                            "{{route('admin.goods.get_category')}}", data,
                            function (json) {

                                for (var i = 0; i < json.length; i++) {

                                    var data = {
                                        id: json[i].id,
                                        value: json[i].name,
                                        parent_id: json[i].parent_id,
                                    }
                                    html = html + $.convertTemplate('#template', data, '');
                                }
                                // 异步请求后， 模板数据全都存在于var html中 下一步获得 类为 category-content的位置 这里有个bug,  应该要放进 ajax里面
                                var categoryContentPosition = $object.data('position');

                                if (categoryContentPosition != "right") {
                                    // categoryContentPosition 不等于 right 找到它的next sibling
                                    var $nextObject = $object.next();
                                    // 首先将 $nextObject里面的内容清空
                                    $nextObject.children().remove();
                                    $nextObject.append(html);
                                    // debugger;
                                    $(".category_checks").iCheck({checkboxClass: 'icheckbox_square-green'});
                                    //将id存在于 category_ids里的 checkbox checked
                                    for (var i = 0; i < category_ids.length; i++) {
                                        $("input[data-uniqueId=categoryIds_" + category_ids[i] + "]").iCheck('check');
                                    }
                                }
                                if (1 == flag) {
                                    parentId = parseInt(parentId);
                                    if (category_ids.indexOf(parentId) < 0) {
                                        category_ids.push(parentId);
                                        category_checked.push(parentName);
                                        $("#hidden-category-id").append("<input  type=\"hidden\" name=\"category_id[]\" id=category_" + parentId + " value=" + parentId + ">");
                                    }
                                }
                            });
                }
            }

            $('body').on('click', '.category-btn', function () {
                // 获得相邻的checkbox
                var $checkbox = $(this).prev().find(':checkbox');
                var id = $checkbox.data('id');
                var name = $checkbox.data('name');
                var $parentCategoryContent = $checkbox.closest('.category-content');
                operator($parentCategoryContent, id, name, 3);
            });
            $('body').on('ifChanged', '.category_checks', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var parentId = $(this).data('parent');
                var $parentCategoryContent = $(this).closest('.category-content');
                if ($(this).is(':checked')) {
                    operator($parentCategoryContent, id, name, 1);
                    addTheOrderCheckedCat(id, parentId, name);
                } else {
                    operator($parentCategoryContent, id, name, 2);
                    removeTheOrderCheckedCat(id);
                }
            });
        });
    </script>

    <!--相册-->

    {!! Html::style(env("APP_URL").'/assets/backend/file-manage/el-Upload/css/pop.css') !!}

    {!! Html::script(env("APP_URL").'/assets/backend/file-manage/bootstrap-treeview/bootstrap-treeview.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/file-manage/el-Upload/js/pop.js?v=20180809') !!}
    <script>
        $("#upload").on("click", function () {
            var el_list = $(this);

            $.addImage(el_list, "selectHighlight", function (data) {

                data.forEach(function (value, index) {
                    $('#mp_menu_table tbody').append($('#top_menu_template').html().replace(/{MENU_ID}/g, hex_md5(new Date().getTime() + '|' + Math.random())).replace(/{url}/g, value.url));
                });


                $('#mp_menu_table tbody').find("input[type = 'radio']").iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });
            });
        });

        function delAlbumImg(_self) {
            var obj = $(_self);
            swal({
                title: "确定删除该橱窗图吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                obj.parents('tr').remove();
                swal("删除成功!", "", "success");

            });
        }
    </script>

    <script>
        var current_action = 1;

        function tabCheckSpec() {
            var spec_nums = $('#module-specs input').length;
            if (spec_nums > 0) {
                var len = $("#module-specs input:checkbox:checked").length;
                if (len <= 0) {
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }

        function tabCheckImages() {
            var img_nums = $('#mp_menu_table tbody tr').length;
            if (img_nums <= 0) {
                return false;
            } else {
                return true;
            }
        }

        $(function () {
            $('.app-action').on('click', function () {
                var that = $(this);
                var id = that.data('id');
                var action = that.data('action');

                i = id - 1;

                if (current_action == 1) {
                    if (!$('input[name="name"]').val()) {
                        swal("请输入商品名称!", "", "warning");
                        return;
                    }

                    if (!$('#brand_id').val()) {
                        swal("请选择商品品牌!", "", "warning");
                        return;
                    }

                    if ($('input[name="category_id[]"]').length == 0) {
                        swal("请选择商品分类!", "", "warning");
                        return;
                    }
                    if (!$('input[name="store_nums"]').val()) {
                        swal("请输入商品数量!", "", "warning");
                        return;
                    }

                    if (!$('input[name="goods_no"]').val()) {
                        swal("请输入商品编号!", "", "warning");
                        return;
                    }

                    if (!$('input[name="market_price"]').val() || !$('input[name="sell_price"]').val()) {
                        swal("请输入商品价格!", "", "warning");
                        return;
                    }

                    if (!$('#model_id').val()) {
                        swal("请选择商品模型!", "", "warning");
                        return;
                    }

                }


                if (id > 5) {
                    if (!tabCheckImages()) {
                        swal("请上传商品橱窗图!", "", "warning");
                        current_action = 5;
                        $(".nav-tabs li").removeClass("active").eq(4).addClass("active");
                        $(".tab-pane").removeClass("active").eq(4).addClass("active");
                        return;
                    }
                }

                $(".nav-tabs li").removeClass("active").eq(i).addClass("active");
                $(".tab-pane").removeClass("active").eq(i).addClass("active");

                current_action = id;
                console.log('current_action:' + current_action)
            });

            $('.app-action-prev').on('click', function () {
                var that = $(this);
                var id = that.data('id');
                current_action = id;
                $(".nav-tabs li").removeClass("active").eq(id - 1).addClass("active");
                $(".tab-pane").removeClass("active").eq(id - 1).addClass("active");
            });
        });

        $('.navbar-header a').click(function () {
            if ($('body').hasClass('mini-navbar')) {
                $('.app-actions').css('left', '200px');
            } else {
                $('.app-actions').css('left', '70px');
            }
        })
    </script>