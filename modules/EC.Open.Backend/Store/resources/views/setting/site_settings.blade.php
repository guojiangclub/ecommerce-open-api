{{--@extends('store-backend::dashboard')

@section ('title','站点设置')

@section('breadcrumbs')
    <h2>站点设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">站点设置</li>
    </ol>
@endsection
@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}--}}
    <style type="text/css">
        input[type=file] {
            width: 72px;
            margin-top: 10px;
        }
    </style>
{{--@stop
@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveSiteSettings')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">站点名称：</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               value="{{settings('store_name')}}"
                               name="store_name" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点LOGO：</label>

                    <div class="col-sm-10">

                        <img src="{{settings('store_logo')}}"@if(empty(settings('store_logo'))) hidden @endif id="img_show" width="100"/>

                        <input id="img_url" type="hidden" name="store_logo" value="{{settings('store_logo')}}"/>
                        <div id="filePicker">选择图片</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商城菜单：</label>

                    <div class="col-sm-10">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="100">菜单名</th>
                                <th width="100">样式</th>
                                <th width="100">点击前图标</th>
                                <th width="100">点击后图标</th>
                                <th>链接</th>
                                <th width="100">排序</th>
                                <th width="120">是否启用</th>
                                <th width="100">操作</th>
                            </tr>
                            </thead>

                            <tbody id="menuBody">
                            @if($menu = settings('menu_list'))
                                @foreach ($menu as $key => $value)
                                    <tr class="menuList" data-id="{{$key}}">
                                        <td>
                                            <input  type="text" name="menu_list[{{$key}}][name]"
                                                   class="form-control" value="{{$value['name']}}">
                                        </td>
                                        <td>
                                            <input  type="text" name="menu_list[{{$key}}][class]"
                                                    class="form-control" value="{{isset($value['class'])?$value['class']:''}}">
                                        </td>
                                        <td>
                                            <label class="block_front img-plus">
                                            @if(!empty($value['image_url']['front']))
                                                <img width="50" src="{{$value['image_url']['front']}}">
                                                <input type="hidden" name="menu_list[{{$key}}][image_url][front]" value="{{$value['image_url']['front']}}">
                                            @endif
                                            <input type="file" name="upload_image" data-name="front" accept="image/*">
                                            </label>
                                        </td>
                                        <td>
                                            <label class="block_after img-plus">
                                            @if(!empty($value['image_url']['after']))
                                                <img width="50" src="{{$value['image_url']['after']}}">
                                                <input type="hidden" name="menu_list[{{$key}}][image_url][after]" value="{{$value['image_url']['after']}}">
                                            @endif
                                            <input type="file" name="upload_image" data-name="after" accept="image/*">
                                            </label>
                                        </td>
                                        <td>
                                            <input  type="text" name="menu_list[{{$key}}][url]"
                                                    class="form-control" value="{{$value['url']}}">
                                        </td>
                                        <td>
                                            <input  type="text" name="menu_list[{{$key}}][sort]"
                                                    class="form-control" onkeyup="value=value.replace(/[^\d]/g,'')" value="{{$value['sort']}}">
                                        </td>
                                        <td>
                                            <input type="radio" value="1"
                                                   name="menu_list[{{$key}}][is_enabled]" {{$value['is_enabled'] == 1 ? 'checked': ''}}>
                                            是
                                            &nbsp;&nbsp;
                                            <input type="radio" value="0"
                                                   name="menu_list[{{$key}}][is_enabled]" {{$value['is_enabled'] == 0 ? 'checked': ''}}>
                                            否
                                        </td>
                                        <td>
                                            <button class="btn btn-primary del_menu" type="button">删除</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="8">
                                    <button type="button" id="add-refund" class="btn btn-w-m btn-info">添加菜单</button>
                                </td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站统计代码：</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" rows="4"
                                  name="store_statistics_js">{{settings('store_statistics_js')}}</textarea>
                    </div>
                </div>


                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存设置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script id="refund-template" type="text/x-template">
        <tr class="menuList" data-id="{NUM}">
            <td>
                <input type="text" name="menu_list[{NUM}][name]" class="form-control" placeholder="菜单名称">
            </td>
            <td>
                <input type="text" name="menu_list[{NUM}][class]" class="form-control" placeholder="样式">
            </td>
            <td>
                <label class="block_front img-plus">
                    <input type="file" name="upload_image" data-name="front"  accept="image/*">
                </label>
            </td>
            <td>
                <label class="block_after img-plus">
                    <input type="file" name="upload_image" data-name="after" accept="image/*">
                </label>
            </td>
            <td>
                <input type="text" name="menu_list[{NUM}][url]" class="form-control" placeholder="链接">
            </td>
            <td>
                <input type="text" name="menu_list[{NUM}][sort]" class="form-control" onkeyup="value=value.replace(/[^\d]/g,'')" placeholder="排序">
            </td>
            <td>
                <input type="radio" value="1" name="menu_list[{NUM}][is_enabled]" checked> 是 &nbsp;&nbsp;
                <input type="radio" value="0" name="menu_list[{NUM}][is_enabled]" > 否
            </td>
            <td>
                <button class="btn btn-primary del_menu"  type="button">删除</button>
            </td>
        </tr>
    </script>
{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}--}}
    <script>
        $(function () {
            var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
            var refund_html = $('#refund-template').html();
            $('#add-refund').click(function() {
                var num = $('.menuList').length;
                $('#menuBody').append(refund_html.replace(/{NUM}/g, num));

                $('#menuBody').find("input[type='radio']").iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });
            });


            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location.reload();
                    });

                }
            });
// 该数据为模拟数据, 需要从服务端获取
//            var app = window.skuBuilder = {};
//            app.bindEvent = function () {
            (function(){
                var body = $('body');
                body.on('change', 'input[name=upload_image]', function () {
                    var el = $(this);
                    var id = el.parents('tr').data('id');
                    var sid = el.parents('tr').data('sid');
                    var name = el.data('name');
                    var file = this.files[0];
                    var form = new FormData();
                    form.append('id', id);
                    form.append('upload_image', file);

                    $.ajax({
                        url: postImgUrl,
                        type: 'POST',
                        data: form,
                        dataType: 'JSON',
                        cache: false,
                        processData: false,
                        contentType: false
                    }).done(function (ret) {
                        var url = ret.url;
                        el.parents('tr').find('.block_'+name+':first').html('<img width="50" src="' + url + '"><input type="hidden" name="menu_list[' + id + '][image_url]['+name+']" value="' + url + '"><input type="file" name="upload_image" data-name="'+name+'" accept="image/*">').removeClass('hidden');


                    }).fail(function () {

                    });
                });
            }());

//            };
                // 初始化Web Uploader
                var uploader = WebUploader.create({
                    auto: true,
                    swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                    server: '{{route('upload.image',['_token'=>csrf_token()])}}',
                    pick: '#filePicker',
                    fileVal: 'upload_image',
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    }
                });
                // 文件上传成功，给item添加成功class, 用样式标记上传成功。
                uploader.on('uploadSuccess', function (file, response) {
                    $('#img_url').val(response.url);
                    $('#img_show').attr('src',response.url);
                    $('#img_show').show();

                });

        })
        $('body').on('click','.del_menu',function () {
            console.log($(this).parent().parent());
            $(this).parent().parent().remove();
        })


    </script>
{{--@stop--}}