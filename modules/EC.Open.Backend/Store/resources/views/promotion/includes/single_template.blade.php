<div class="form-group">
    <label class="col-sm-2 control-label">单品列表：</label>
    <div class="col-sm-10">
        @if($discount->path)
            <a no-pjax href="{{$discount->path}}">点击下载已导入的折扣文件</a>
        @else
            <table class="table table-bordered" id="discount-table">
                <thead>
                <tr>
                    <th>SKU</th>
                    <th>折扣设置</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="page-discount-list">


                </tbody>
                <tfoot>
                <tr>
                    <td class="pages page_box" colspan="3">
                        <ul class="pagination" style="display: none;">
                            <!-- Previous Page Link -->
                            <li><a class="dis_prev">上一页</a></li>
                            <li class="dis_num">
                            <span>
                                <i class="dis_current_page">1</i><i
                                        style="padding:0 3px;">/</i><i class="dis_total"></i>
                            </span>
                            </li>
                            <!-- Next Page Link -->
                            <li><a class="dis_next">下一页</a></li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <button class="btn btn-primary" id="add-condition" type="button">添加单品</button>
                    </td>
                </tr>
                </tfoot>
            </table>
        @endif


    </div>
</div>


