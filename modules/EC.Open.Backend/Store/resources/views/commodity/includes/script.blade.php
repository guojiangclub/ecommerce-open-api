<script>
    //  统一上下架
    $('.checkbox').on('ifChecked', function (event) {
	    var val = $(this).val();
	    $(this).parents('.goods' + val).addClass('selected');
    });

    $('.checkbox').on('ifUnchecked', function (event) {
	    var val = $(this).val();
	    $(this).parents('.goods' + val).removeClass('selected');
    });

    // 商品批量上下架
    $('.lineGoods').on('click', function () {
	    if ($('.checkbox ').length <= 0) {
		    swal("操作失败", "当前无可数据", "warning");
		    return false;
	    }

	    var num = $('.selected').length;
	    if (num == 0) {
		    swal("注意", "请勾选需要操作的商品", "warning");
		    return false;
	    }
	    $('.batch').ladda().ladda('start');

	    var arr = [];
	    for (var i = 0; i < num; i++) {
		    var gid = $('.selected').eq(i).attr('gid');
		    arr[i] = gid;
	    }

		var status = $(this).data('status');
		var url = "{{route('admin.goods.saveIsDel',['lineGoods'=>1])}}";  //删除
		if (status == 2) {
			url = "{{route('admin.goods.saveIsDel',['lineGoods'=>2])}}"; //下架
		} else if (status == 3) {
			url = "{{route('admin.goods.saveIsDel',['lineGoods'=>0])}}"; //上架
		}

	    $.post(url, {gid: arr, token: _token}, function (result) {
		    swal({
				    title: "操作成功",
				    text: result.data.error_list,
				    type: "success"
			    },
			    function () {
				    location.reload();
			    });
	    });
    });


    /**
     * 导出搜索、勾选商品
     */
    $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
	    var $this = $(this),
		    href = $this.attr('href'),
		    modalUrl = $(this).data('url');

	    var param = funcUrlDel('page');
	    var num = $('.selected').length;

	    if (param == '' && num == 0) {
		    swal("注意", "请先进行搜索或勾选商品再使用此功能", "warning");
		    return;
	    }
	    var gids = '';
	    if (num != 0) {
		    for (var i = 0; i < num; i++) {
			    var gid = $('.selected').eq(i).attr('gid');
			    gids += 'ids[]=' + gid + '&';
		    }
	    }

	    var url = '{{route('admin.goods.getExportData')}}';
	    var type = $(this).data('type');

	    if (param == '') {
		    url = url + '?type=' + type + '&' + gids;
	    } else {
		    url = url + '?' + param + '&type=' + type + '&' + gids;
	    }

	    $(this).data('link', url);

	    if (modalUrl) {
		    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
		    $target.modal('show');
		    $target.html('').load(modalUrl, function () {

		    });
	    }
    });


    /**
     * 批量修改商品标题
     */
    $(document).on('click.modal.data-api', '[data-toggle="modal-modify-title"]', function (e) {
	    var $this = $(this),
		    href = $this.attr('href'),
		    modalUrl = $(this).data('link');

	    var num = $('.selected').length;

	    if (num == 0) {
		    swal("注意", "请先勾选商品再使用此功能", "warning");
		    return;
	    }
	    var gids = '';

	    for (var i = 0; i < num; i++) {
		    var gid = $('.selected').eq(i).attr('gid');
		    gids += 'ids[]=' + gid + '&';
	    }

	    modalUrl = modalUrl + '?' + gids;
	    $(this).data('url', modalUrl);

	    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
	    $target.modal('show');
	    $target.html('').load(modalUrl, function () {

	    });

    });

    /**
     * 删除商品
     */
    function delete_goods(delete_url, tips) {
	    swal({
			    title: "确认删除该商品吗？",
			    text: tips,
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#DD6B55",
			    confirmButtonText: "删除",
			    cancelButtonText: "取消",
			    closeOnConfirm: false
		    },
		    function () {
			    $.post(delete_url, {token: _token}, function (result) {
				    if (result.status) {
					    swal({
							    title: "删除成功",
							    text: "",
							    type: "success"
						    },
						    function () {
							    location.reload();
						    });
				    }
			    });
		    });
    }

    //删除商品
    $('.off-goods').on('click', function () {
	    var deleteUrl = $(this).data('href');
		delete_goods(deleteUrl, '删除后可以在已删除商品列表恢复');
    });

    //彻底删除商品
    $('.delete-goods').on('click', function () {
	    var deleteUrl = $(this).data('href');
	    delete_goods(deleteUrl, '删除后不可恢复');
    });

    $('.restore-goods').on('click', function () {
	    var restoreUrl = $(this).data('href');
	    $.post(restoreUrl, {token: _token}, function (result) {
		    if (result.status) {
			    swal({
					    title: "恢复成功",
					    text: "",
					    type: "success"
				    },
				    function () {
					    location.reload();
				    });
		    } else {
			    swal('恢复失败', '', 'error');
		    }
	    });
    });

</script>