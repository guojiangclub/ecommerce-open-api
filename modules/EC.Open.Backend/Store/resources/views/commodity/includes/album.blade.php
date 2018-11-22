<style type="text/css">
    /*#filePicker,.webuploader-pick{display: block; z-index: 9999; position: absolute;}*/
</style>
<div class="tab-pane" id="tab_5">
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-bordered table-stripped" id="mp_menu_table">
                <thead>
                <tr>
                    <th>
                        主图
                    </th>
                    <th>
                        预览
                    </th>
                    <th>
                        图片链接
                    </th>

                    <th>
                        排序(数字越大排在越前)
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(isset($goods_info))
                    @foreach($goods_info->GoodsPhotos as $key => $val)
                        <tr data-id="{{$val['code']}}" class="top_menu" id="menu_id_{{$val['code']}}">
                            <td valign="middle"><input name="_is_default" type="radio" value="{{$val['code']}}" {{$val->CheckedStatus}}  /></td>
                            <td>
                                <img src="{{$val['url']}}" style="max-width: 100px;">
                            </td>
                            <td>
                                <input type="hidden" name="_imglist[{{$val['code']}}][url]" value="{{$val['url']}}">
                                <input type="text" class="form-control" disabled="" value="{{$val['url']}}">
                            </td>

                            <td>
                                <input type="text" class="form-control" name="_imglist[{{$val['code']}}][sort]" value="{{$val['sort']}}">
                            </td>
                            <td>
                                <a href="javascript:;" class="btn btn-white" onclick="delAlbumImg(this)"><i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <div id="upload" class="btn btn-primary">选择图片</div>

                <div class="clearfix"></div>
            </div>
        </div>
        <script type="text/x-template" id="top_menu_template">
            <tr data-id="{MENU_ID}" class="top_menu" id="menu_id_{MENU_ID}">
                <td valign="middle">
                    <input name="_is_default" type="radio" value="{MENU_ID}"  />
                </td>
                <td>
                    <img src="{url}" style="max-width: 100px;">
                </td>
                <td>
                    <input type="hidden" name="_imglist[{MENU_ID}][url]" value="{url}">
                    <input type="text" class="form-control" disabled="" value="{url}">
                </td>

                <td>
                    <input type="text" class="form-control" name="_imglist[{MENU_ID}][sort]" value="9">
                </td>
                <td>
                    <a href="javascript:;" class="btn btn-white"
                       onclick="delAlbumImg(this)"><i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        </script>
    </div>
    <div class="app-actions">
        <a data-id="5" data-action="next" class="btn btn-success app-action-prev">«上一步</a>
        <a data-id="7" data-action="next" class="btn btn-success app-action">下一步»</a>
        <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
               value="保存">
    </div>
</div><!-- /.tab-pane -->