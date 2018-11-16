<script>
    $(function () {

        /*全选操作*/
        $("input[class='check-all']").on('ifClicked', function (event) {
            var that = $(this);
            setTimeout(function () {
                if (that.is(':checked')) {
                    $('.el-check-img').iCheck('check');
                    $('.el-check-img').addClass('el-selected');
                } else {
                    $('.el-check-img').iCheck('uncheck');
                    $('.el-check-img').removeClass('el-selected');
                }
            }, 0);
        });

        /*checkbox状态改变，监听批量操作按钮状态*/
        $("input[class='el-check-img']").on('ifChanged', function (event) {
            var that = $(this);

            setTimeout(function () {
                if(that.is(':checked')) {
                    that.addClass('el-selected');
                }else{
                    that.removeClass('el-selected');
                }

                var operateAll = $('.el-image-operate-all');
                var el_image_operate_category = $('.el-image-operate-category');
                var elements = $('.el-selected');
                var len = elements.length;
                if (len > 0) {
                    operateAll.removeClass('grey');
                    var ids = getCheckAllValue();
                    var cid = el_image_operate_category.data('id');
                    var url = el_image_operate_category.data('link') + '?ids=' + ids + '&cid=' + cid;

                    el_image_operate_category.attr('data-toggle', 'modal');
                    el_image_operate_category.attr('data-url', url);
                } else {
                    operateAll.addClass('grey');
                    el_image_operate_category.attr('data-toggle', '');
                    el_image_operate_category.attr('data-url', '');
                }
            });

        });


        /*获取全选的值*/
        function getCheckAllValue() {
            var valArr = new Array;
            $(".el-selected").each(function (i) {
                valArr[i] = $(this).val();
            });
            return valArr.join(',');
        }


        /*删除图片*/
        function deleteImage(id,type) {
            $.post("{{ route('admin.image.delete') }}", {
                'id': id,
                'type':type,
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

        $('.el-delete-image').on('click', function () {
            var image_id;
            var type;
            var that=$(this);
            if(that.hasClass('grey')) return;

            if(that.hasClass('el-image-operate-all')) {
                 image_id = getCheckAllValue();
                type='batch';
            }else{
                image_id = $(this).data('id');
                type='single';
            }

            swal({
                title: "您确认删除该图片吗？",
                text: "若删除，不会对目前已使用该图片的相关业务造成影响。",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                deleteImage(image_id,type);
            });
        });


    });
</script>